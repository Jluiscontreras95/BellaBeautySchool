<?php

namespace App\Services;

use App\Models\KnowledgeEntry;
use Illuminate\Support\Collection;
use Laravel\Ai\Embeddings;

class KnowledgeBase
{
    /**
     * Modelo de embeddings usado por la base de conocimiento.
     */
    public function embeddingsModel(): string
    {
        return (string) config('ai.models.embeddings', 'text-embedding-3-small');
    }

    /**
     * Genera el vector (embedding) de un texto.
     *
     * @return array<int, float>
     */
    public function embeddingFor(string $text): array
    {
        return Embeddings::for([$text])
            ->generate(model: $this->embeddingsModel())
            ->first();
    }

    /**
     * Calcula la similitud coseno entre dos vectores.
     *
     * @param  array<int, float>  $a
     * @param  array<int, float>  $b
     */
    public function cosineSimilarity(array $a, array $b): float
    {
        $dot = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        foreach ($a as $i => $value) {
            $other = $b[$i] ?? 0.0;
            $dot += $value * $other;
            $normA += $value * $value;
            $normB += $other * $other;
        }

        if ($normA == 0.0 || $normB == 0.0) {
            return 0.0;
        }

        return $dot / (sqrt($normA) * sqrt($normB));
    }

    /**
     * Indexa (genera y guarda) el embedding de una entrada.
     */
    public function index(KnowledgeEntry $entry): void
    {
        $entry->update([
            'embedding' => $this->embeddingFor($entry->searchableText()),
        ]);
    }

    /**
     * Reindexa todas las entradas activas.
     */
    public function indexAll(): int
    {
        $count = 0;

        foreach (KnowledgeEntry::active()->cursor() as $entry) {
            $this->index($entry);
            $count++;
        }

        return $count;
    }

    /**
     * Busca en la base de conocimiento por similitud semántica.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function search(string $query, int $limit = 5, float $threshold = 0.55): Collection
    {
        try {
            $queryEmbedding = $this->embeddingFor($query);
        } catch (\Throwable $e) {
            return $this->keywordSearch($query, $limit);
        }

        $results = KnowledgeEntry::active()
            ->whereNotNull('embedding')
            ->get()
            ->map(function (KnowledgeEntry $entry) use ($queryEmbedding): ?array {
                $embedding = $entry->embedding;

                if (! is_array($embedding) || empty($embedding)) {
                    return null;
                }

                return [
                    'score' => $this->cosineSimilarity($embedding, $queryEmbedding),
                    'category' => $entry->category,
                    'title' => $entry->title,
                    'content' => $entry->content,
                    'metadata' => $entry->metadata ?? [],
                ];
            })
            ->filter()
            ->filter(fn (array $result): bool => $result['score'] >= $threshold)
            ->sortByDesc('score')
            ->take($limit)
            ->values();

        if ($results->isEmpty()) {
            return $this->keywordSearch($query, $limit);
        }

        return $results;
    }

    private function keywordSearch(string $query, int $limit = 5): Collection
    {
        $terms = collect(preg_split('/\s+/', mb_strtolower($query)))->filter(fn ($t) => mb_strlen($t) > 2)->take(6);

        if ($terms->isEmpty()) {
            return collect();
        }

        $builder = KnowledgeEntry::active();

        $builder->where(function ($q) use ($terms) {
            foreach ($terms as $term) {
                $q->orWhere('title', 'like', "%{$term}%")->orWhere('content', 'like', "%{$term}%");
            }
        });

        return $builder->limit($limit)->get()->map(fn (KnowledgeEntry $e) => [
            'score' => 0.6,
            'category' => $e->category,
            'title' => $e->title,
            'content' => $e->content,
            'metadata' => $e->metadata ?? [],
        ]);
    }

    /**
     * Formatea los resultados para que el agente los pueda citar.
     */
    public function searchForAgent(string $query, int $limit = 5): string
    {
        $results = $this->search($query, $limit);

        if ($results->isEmpty()) {
            return 'No encontré información oficial sobre eso en la base de conocimiento.';
        }

        return $results
            ->map(fn (array $result, int $index): string => sprintf(
                "[%d] %s (%s):\n%s",
                $index + 1,
                $result['title'],
                $result['category'],
                $result['content'],
            ))
            ->implode("\n\n");
    }
}
