<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Services\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    private function nextWeekday(): Carbon
    {
        $date = today();

        while ($date->isWeekend()) {
            $date = $date->addDay();
        }

        return $date;
    }

    public function test_an_appointment_is_created_with_a_confirmation_code(): void
    {
        $appointment = app(BookingService::class)->create([
            'name' => 'Ana Pérez',
            'email' => 'ana@example.com',
            'phone' => '3001234567',
            'interest' => 'Programa de Manicurista',
            'preferred_date' => $this->nextWeekday()->toDateString(),
            'preferred_time' => '10:00',
        ]);

        $this->assertInstanceOf(Appointment::class, $appointment);
        $this->assertNotNull($appointment->confirmation_code);
        $this->assertSame('10:00:00', $appointment->preferred_time);
        $this->assertSame(120, $appointment->duration_minutes);
    }

    public function test_back_to_back_appointments_do_not_overlap(): void
    {
        $date = $this->nextWeekday();

        app(BookingService::class)->create([
            'name' => 'Ana Pérez',
            'email' => 'ana@example.com',
            'phone' => '3001234567',
            'interest' => 'Programa de Manicurista',
            'preferred_date' => $date->toDateString(),
            'preferred_time' => '10:00',
        ]);

        app(BookingService::class)->create([
            'name' => 'Luis Gómez',
            'email' => 'luis@example.com',
            'phone' => '3007654321',
            'interest' => 'Conocer el Studio',
            'preferred_date' => $date->toDateString(),
            'preferred_time' => '12:00',
        ]);

        $this->assertSame(2, Appointment::count());
    }

    public function test_overlapping_times_are_rejected(): void
    {
        $date = $this->nextWeekday();

        app(BookingService::class)->create([
            'name' => 'Ana Pérez',
            'email' => 'ana@example.com',
            'phone' => '3001234567',
            'interest' => 'Programa de Manicurista',
            'preferred_date' => $date->toDateString(),
            'preferred_time' => '10:00',
        ]);

        $this->expectException(ValidationException::class);

        // 11:00 (2h) se solapa con 10:00–12:00.
        app(BookingService::class)->create([
            'name' => 'Luis Gómez',
            'email' => 'luis@example.com',
            'phone' => '3007654321',
            'interest' => 'Conocer el Studio',
            'preferred_date' => $date->toDateString(),
            'preferred_time' => '11:00',
        ]);
    }

    public function test_weekend_dates_are_rejected(): void
    {
        $saturday = today()->next(Carbon::SATURDAY);

        $this->expectException(ValidationException::class);

        app(BookingService::class)->create([
            'name' => 'Ana Pérez',
            'email' => 'ana@example.com',
            'phone' => '3001234567',
            'interest' => 'Programa de Manicurista',
            'preferred_date' => $saturday->toDateString(),
            'preferred_time' => '10:00',
        ]);
    }

    public function test_availability_marks_booked_slots(): void
    {
        $date = $this->nextWeekday();

        app(BookingService::class)->create([
            'name' => 'Ana Pérez',
            'email' => 'ana@example.com',
            'phone' => '3001234567',
            'interest' => 'Programa de Manicurista',
            'preferred_date' => $date->toDateString(),
            'preferred_time' => '10:00',
        ]);

        $availability = app(BookingService::class)->availabilityFor($date);

        $slots = collect($availability['slots'])->pluck('available', 'time');

        $this->assertFalse($slots['10:00']);
        $this->assertTrue($slots['12:00']);
        $this->assertTrue($slots['16:00']);
        $this->assertTrue($slots['18:00']);
    }
}
