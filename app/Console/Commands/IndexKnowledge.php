<?php

namespace App\Console\Commands;

use App\Services\KnowledgeBase;
use Illuminate\Console\Command;

class IndexKnowledge extends Command
{
    protected $signature = 'bela:index-knowledge';

    protected $description = 'Genera (o regenera) los embeddings de la base de conocimiento de BELA.';

    public function handle(KnowledgeBase $knowledgeBase): int
    {
        if (blank(env('OPENAI_API_KEY'))) {
            $this->error('OPENAI_API_KEY no está configurada en el archivo .env.');

            return self::FAILURE;
        }

        $this->info('Indexando entradas de conocimiento...');

        $count = $knowledgeBase->indexAll();

        $this->info("Se indexaron {$count} entradas correctamente.");

        return self::SUCCESS;
    }
}
