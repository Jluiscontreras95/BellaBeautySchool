<?php

namespace App\Ai\Tools;

use App\Services\BookingService;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Validation\ValidationException;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class CreateAppointment implements Tool
{
    public function __construct(protected ?BookingService $bookings = null)
    {
        $this->bookings ??= app(BookingService::class);
    }

    public function description(): Stringable|string
    {
        return 'Crea una reserva de visita guiada en BELA Beauty Studio con los datos del usuario. Requiere nombre, email, teléfono, interés y fecha (YYYY-MM-DD) + hora (HH:MM). Antes de crear la cita, valida la disponibilidad con la herramienta de disponibilidad. Devuelve el código de confirmación o un error claro si el horario ya está ocupado.';
    }

    public function handle(Request $request): Stringable|string
    {
        try {
            $appointment = $this->bookings->create([
                'name' => trim($request->string('name')),
                'email' => strtolower(trim($request->string('email'))),
                'phone' => trim($request->string('phone')),
                'interest' => trim($request->string('interest')),
                'preferred_date' => $request->string('preferred_date'),
                'preferred_time' => $request->string('preferred_time'),
                'message' => $request->string('message', ''),
            ]);

            return (string) json_encode([
                'ok' => true,
                'confirmation_code' => $appointment->confirmation_code,
                'name' => $appointment->name,
                'interest' => $appointment->interest,
                'date' => $appointment->preferred_date->toDateString(),
                'time' => $appointment->timeString(),
                'message' => 'Reserva creada correctamente. El equipo de BELA contactará para confirmarla.',
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } catch (ValidationException $exception) {
            $errors = collect($exception->errors())->flatten()->unique()->implode(' ');

            return (string) json_encode([
                'ok' => false,
                'error' => $errors,
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->description('Nombre completo del interesado.')->required(),
            'email' => $schema->string()->description('Correo electrónico válido.')->required(),
            'phone' => $schema->string()->description('Número de teléfono o WhatsApp.')->required(),
            'interest' => $schema->string()->description('Interés de la visita (ej. Programa de Manicurista, Cursos intensivos, Conocer el Studio).')->required(),
            'preferred_date' => $schema->string()->description('Fecha en formato YYYY-MM-DD (lunes a viernes).')->required(),
            'preferred_time' => $schema->string()->description('Hora en formato HH:MM dentro del horario de atención (09:00 a 20:00).')->required(),
            'message' => $schema->string()->description('Mensaje o comentario opcional.'),
        ];
    }
}
