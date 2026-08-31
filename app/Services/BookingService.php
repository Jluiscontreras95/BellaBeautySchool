<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class BookingService
{
    /**
     * Duración por defecto de una cita (minutos).
     */
    public static function duration(): int
    {
        return (int) config('booking.duration_minutes', 120);
    }

    /**
     * Horarios (franjas) ofrecidos por defecto.
     *
     * @return string[]
     */
    public static function slots(): array
    {
        return (array) config('booking.slots', ['10:00', '12:00', '16:00', '18:00']);
    }

    /**
     * Horario laboral como [open, close] en HH:MM.
     *
     * @return array{open: string, close: string}
     */
    public static function businessHours(): array
    {
        return [
            'open' => config('booking.business_hours.open', '09:00'),
            'close' => config('booking.business_hours.close', '20:00'),
        ];
    }

    /**
     * Normaliza una hora a formato HH:MM.
     */
    public static function normalizeTime(string $time): string
    {
        return substr(trim($time), 0, 5);
    }

    /**
     * Citas activas (que bloquean agenda) de una fecha concreta.
     */
    public function activeForDate(Carbon $date): Collection
    {
        return Appointment::query()
            ->whereDate('preferred_date', $date->toDateString())
            ->whereIn('status', Appointment::ACTIVE_STATUSES)
            ->get();
    }

    /**
     * Determina si una nueva cita se solaparía con otra ya existente.
     */
    public function hasOverlap(Carbon $date, string $time, int $duration): bool
    {
        $start = $date->copy()->setTimeFromTimeString(self::normalizeTime($time));
        $end = $start->copy()->addMinutes($duration);

        foreach ($this->activeForDate($date) as $existing) {
            if ($existing->startsAt()->lt($end) && $existing->endsAt()->gt($start)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Indica si una fecha es apta (día laborable dentro del rango permitido).
     */
    public function validateDate(Carbon $date): ?string
    {
        $min = today()->startOfDay();
        $max = today()->addDays((int) config('booking.max_future_days', 90))->endOfDay();

        if ($date->lt($min)) {
            return 'La fecha no puede estar en el pasado.';
        }

        if ($date->gt($max)) {
            return 'Esa fecha queda demasiado lejos para agendar.';
        }

        if ($date->isWeekend()) {
            return 'Las visitas están disponibles de lunes a viernes.';
        }

        return null;
    }

    /**
     * Valida que la franja horaria esté dentro del horario laboral.
     */
    public function validateTime(Carbon $date, string $time, int $duration): ?string
    {
        $start = $date->copy()->setTimeFromTimeString(self::normalizeTime($time));
        $end = $start->copy()->addMinutes($duration);

        $hours = self::businessHours();
        $open = $date->copy()->setTimeFromTimeString($hours['open']);
        $close = $date->copy()->setTimeFromTimeString($hours['close']);

        if ($start->lt($open) || $end->gt($close)) {
            return 'Ese horario está fuera del horario de atención.';
        }

        return null;
    }

    /**
     * Lanza una ValidationException si la cita no es posible.
     */
    public function assertAvailable(Carbon $date, string $time, int $duration): void
    {
        if ($error = $this->validateDate($date)) {
            throw ValidationException::withMessages(['preferred_date' => $error]);
        }

        if ($error = $this->validateTime($date, $time, $duration)) {
            throw ValidationException::withMessages(['preferred_time' => $error]);
        }

        if ($this->hasOverlap($date, $time, $duration)) {
            throw ValidationException::withMessages([
                'preferred_time' => 'Ese horario acaba de ser reservado. Por favor elige otro.',
            ]);
        }
    }

    /**
     * Disponibilidad (franjas + estado) para una fecha.
     *
     * @return array<string, mixed>
     */
    public function availabilityFor(Carbon $date, ?int $duration = null): array
    {
        $duration ??= self::duration();
        $slots = self::slots();

        $slots = collect($slots)->map(function (string $slot) use ($date, $duration): array {
            $available = $this->validateDate($date) === null
                && $this->validateTime($date, $slot, $duration) === null
                && ! $this->hasOverlap($date, $slot, $duration);

            return ['time' => $slot, 'available' => $available];
        })->values()->all();

        return [
            'date' => $date->toDateString(),
            'is_available' => $this->validateDate($date) === null,
            'duration_minutes' => $duration,
            'slots' => $slots,
            'message' => $this->validateDate($date),
        ];
    }

    /**
     * Crea una cita de forma segura frente a condiciones de carrera.
     *
     * Serializa las escrituras por fecha mediante un lock atómico y revalida
     * el solapamiento dentro de una transacción, evitando el overbooking.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Appointment
    {
        $date = Carbon::parse($attributes['preferred_date'])->startOfDay();
        $time = self::normalizeTime((string) ($attributes['preferred_time'] ?? ''));
        $duration = (int) ($attributes['duration_minutes'] ?? self::duration());

        $lock = Cache::lock('booking:'.$date->toDateString(), 15);

        try {
            $appointment = $lock->block(15, function () use ($attributes, $date, $time, $duration): Appointment {
                return DB::transaction(function () use ($attributes, $date, $time, $duration): Appointment {
                    $this->assertAvailable($date, $time, $duration);

                    $attributes['preferred_date'] = $date->toDateString();
                    $attributes['preferred_time'] = $time.':00';
                    $attributes['duration_minutes'] = $duration;
                    $attributes['status'] = Appointment::STATUS_PENDING;
                    $attributes['confirmation_code'] = $this->generateConfirmationCode();

                    return Appointment::create($attributes);
                });
            });

            return $appointment;
        } catch (Throwable $throwable) {
            throw $throwable;
        } finally {
            $lock->release();
        }
    }

    /**
     * Genera un código de confirmación único.
     */
    protected function generateConfirmationCode(): string
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (Appointment::where('confirmation_code', $code)->exists());

        return $code;
    }
}
