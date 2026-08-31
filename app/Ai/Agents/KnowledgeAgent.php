<?php

namespace App\Ai\Agents;

use App\Ai\Tools\SearchKnowledge;
use Laravel\Ai\Attributes\MaxSteps;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\CanActAsTool;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Promptable;
use Stringable;

#[MaxSteps(4)]
class KnowledgeAgent implements Agent, CanActAsTool, HasTools
{
    use Promptable;

    public function name(): string
    {
        return 'bela_knowledge';
    }

    public function description(): Stringable|string
    {
        return 'Responde preguntas factuales sobre BELA Beauty Studio usando la base de conocimiento interna (programas, duración, horarios, precios, requisitos, sedes, comunidad, etc.). Usa este agente cuando el usuario pida información sobre los servicios de BELA.';
    }

    public function instructions(): Stringable|string
    {
        return 'Eres el especialista de conocimiento de BELA Beauty Studio. Responde SIEMPRE en español, con claridad, calidez y precisión. Busca en la base de conocimiento interna antes de responder cualquier dato concreto (programas, duración, precios, horarios, requisitos). Si la información no está en la base, indícalo con honestidad y ofrece agendar una visita para obtener más detalles. No inventes datos: cita únicamente lo que encuentres en la base de conocimiento. Mantén las respuestas concisas y útiles.';
    }

    public function tools(): iterable
    {
        return [
            new SearchKnowledge,
        ];
    }
}
