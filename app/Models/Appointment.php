<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Appointment extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Estados que bloquean agenda (cuentan para el overbooking).
     */
    public const ACTIVE_STATUSES = [self::STATUS_PENDING, self::STATUS_CONFIRMED];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'interest',
        'preferred_date',
        'preferred_time',
        'duration_minutes',
        'message',
        'status',
        'confirmation_code',
    ];

    protected function casts(): array
    {
        return [
            'preferred_date' => 'date',
            'duration_minutes' => 'integer',
        ];
    }

    /**
     * Fecha y hora de inicio de la cita.
     */
    public function startsAt(): Carbon
    {
        return $this->preferred_date
            ->copy()
            ->setTimeFromTimeString($this->timeString());
    }

    /**
     * Fecha y hora de fin de la cita.
     */
    public function endsAt(): Carbon
    {
        return $this->startsAt()->addMinutes($this->duration_minutes ?: config('booking.duration_minutes', 120));
    }

    /**
     * Hora normalizada (HH:MM).
     */
    public function timeString(): string
    {
        return substr((string) $this->preferred_time, 0, 5);
    }

    /**
     * Indica si esta cita se solapa con otra en el tiempo.
     */
    public function overlaps(Appointment $other): bool
    {
        return $this->startsAt()->lt($other->endsAt())
            && $other->startsAt()->lt($this->endsAt());
    }

    public function isActive(): bool
    {
        return in_array($this->status, self::ACTIVE_STATUSES, true);
    }
}
