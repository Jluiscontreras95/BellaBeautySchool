<?php

namespace App\Ai\Agents;

use App\Ai\Tools\CheckAvailability;
use App\Ai\Tools\CreateAppointment;
use Laravel\Ai\Attributes\MaxSteps;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\CanActAsTool;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Promptable;
use Stringable;

#[MaxSteps(8)]
class BookingAgent implements Agent, CanActAsTool, HasTools
{
    use Promptable;

    public function name(): string
    {
        return 'bela_booking';
    }

    public function description(): Stringable|string
    {
        return 'Gestiona las reservas de visitas guiadas en BELA Beauty Studio: consulta disponibilidad real y crea citas. Usa este agente cuando el usuario quiera agendar, conocer horarios disponibles o reservar una visita.';
    }

    public function instructions(): Stringable|string
    {
        $today = now()->toDateString();

        return "Eres el asistente de reservas de BELA Beauty Studio. Responde SIEMPRE en español, de forma amable y concisa. Hoy es {$today}. Tu objetivo es agendar visitas guiadas de 2 horas (lunes a viernes, 09:00-20:00). Reglas estrictas: 1) Si el usuario da una fecha, verifica disponibilidad con CheckAvailability (convierte fechas naturales como 'mañana', 'próximo lunes' o '3 de septiembre' a YYYY-MM-DD). 2) Si el usuario proporciona TODOS los datos (nombre, email, teléfono, interés, fecha YYYY-MM-DD y hora HH:MM), verifica disponibilidad y CREA la cita con CreateAppointment usando EXACTAMENTE la hora solicitada, sin pedir confirmación adicional. 3) Si el horario solicitado está ocupado, NO reserves en otro horario; informa que está ocupado y ofrece los horarios libres de ese día para que el usuario elija. 4) Si faltan datos, pide SOLO los que faltan. 5) Nunca inventes datos. Al crear con éxito, reporta EXACTAMENTE los datos del resultado del tool (fecha, hora y código), sin alterar la hora.";
    }

    public function tools(): iterable
    {
        return [
            new CheckAvailability,
            new CreateAppointment,
        ];
    }
}
