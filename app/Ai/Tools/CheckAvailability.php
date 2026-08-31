<?php

namespace App\Ai\Tools;

use App\Services\BookingService;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Carbon;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class CheckAvailability implements Tool
{
    public function __construct(protected ?BookingService $bookings = null)
    {
        $this->bookings ??= app(BookingService::class);
    }

    public function description(): Stringable|string
    {
        return 'Consulta la disponibilidad real de BELA para una fecha concreta (YYYY-MM-DD). Devuelve las franjas horarias disponibles y si la fecha está abierta a reservas. Úsala siempre antes de crear una cita o de confirmar un horario al usuario.';
    }

    public function handle(Request $request): Stringable|string
    {
        $date = Carbon::parse($request->string('date'))->startOfDay();

        return (string) json_encode(
            $this->bookings->availabilityFor($date),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
        );
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'date' => $schema
                ->string()
                ->description('Fecha a consultar en formato YYYY-MM-DD.')
                ->required(),
        ];
    }
}
