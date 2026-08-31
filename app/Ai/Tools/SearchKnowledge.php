<?php

namespace App\Ai\Tools;

use App\Services\KnowledgeBase;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class SearchKnowledge implements Tool
{
    public function __construct(protected ?KnowledgeBase $knowledgeBase = null)
    {
        $this->knowledgeBase ??= app(KnowledgeBase::class);
    }

    public function description(): Stringable|string
    {
        return 'Busca información oficial y actualizada sobre BELA Beauty Studio (programas, duración, horarios, precios, requisitos, sedes, comunidad, reservas, etc.) en la base de conocimiento interna. Usa esta herramienta antes de responder cualquier pregunta fáctica sobre BELA.';
    }

    public function handle(Request $request): Stringable|string
    {
        return $this->knowledgeBase->searchForAgent($request->string('query'));
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema
                ->string()
                ->description('La pregunta o el tema que se desea consultar.')
                ->required(),
        ];
    }
}
