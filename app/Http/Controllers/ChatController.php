<?php

namespace App\Http\Controllers;

use App\Ai\Agents\BelaOrchestrator;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Laravel\Ai\Responses\StreamableAgentResponse;
use Laravel\Ai\Responses\StreamedAgentResponse;

class ChatController extends Controller
{
    /**
     * Transmite (SSE) la respuesta del orquestador de agentes de BELA.
     */
    public function stream(Request $request): StreamableAgentResponse
    {
        if (blank(config('ai.providers.openai.key'))) {
            abort(503, 'La asistente no está disponible todavía. Configura OPENAI_API_KEY en el archivo .env.');
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'conversation_id' => ['required', 'string', 'max:36'],
        ]);

        $message = trim($validated['message']);
        $conversationId = $validated['conversation_id'];

        $agent = new BelaOrchestrator($conversationId);

        return $agent
            ->stream($message)
            ->then(function (StreamedAgentResponse $response) use ($conversationId, $message): void {
                ChatMessage::create([
                    'conversation_id' => $conversationId,
                    'role' => ChatMessage::ROLE_USER,
                    'content' => $message,
                ]);

                $text = trim($response->text);

                if ($text !== '') {
                    ChatMessage::create([
                        'conversation_id' => $conversationId,
                        'role' => ChatMessage::ROLE_ASSISTANT,
                        'content' => $text,
                    ]);
                }
            });
    }
}
