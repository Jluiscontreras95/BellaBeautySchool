<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Duración de las citas
    |--------------------------------------------------------------------------
    |
    | Cada reserva ocupa este número de minutos. Se usa para detectar
    | solapamientos (overbooking) y para calcular la disponibilidad real.
    |
    */

    'duration_minutes' => (int) env('BOOKING_DURATION_MINUTES', 120),

    /*
    |--------------------------------------------------------------------------
    | Horarios predeterminados
    |--------------------------------------------------------------------------
    |
    | Franjas que se ofrecen por defecto en el formulario de reserva.
    |
    */

    'slots' => ['10:00', '12:00', '16:00', '18:00'],

    /*
    |--------------------------------------------------------------------------
    | Horario laboral
    |--------------------------------------------------------------------------
    |
    | Rango dentro del cual es posible agendar una cita (formato HH:MM).
    | No se permiten citas que empiecen antes o terminen después.
    |
    */

    'business_hours' => [
        'open' => '09:00',
        'close' => '20:00',
    ],

    /*
    |--------------------------------------------------------------------------
    | Antelación máxima
    |--------------------------------------------------------------------------
    |
    | Número máximo de días en el futuro para agendar una visita.
    |
    */

    'max_future_days' => (int) env('BOOKING_MAX_FUTURE_DAYS', 90),

];
