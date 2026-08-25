<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['required', 'string', 'max:30'],
            'interest' => ['required', 'string', 'max:80'],
            'preferred_date' => ['required', 'date', 'after_or_equal:today'],
            'preferred_time' => ['required', 'in:10:00,12:00,16:00,18:00'],
            'message' => ['nullable', 'string', 'max:500'],
            'consent' => ['accepted'],
        ])->after(function ($validator) use ($request): void {
            $date = $request->input('preferred_date');
            $time = $request->input('preferred_time');
            $parsedDate = $date && strtotime($date) !== false ? Carbon::parse($date) : null;

            if ($parsedDate?->isWeekend()) {
                $validator->errors()->add('preferred_date', 'Selecciona un día entre lunes y viernes.');
            }

            if ($parsedDate && $time && Appointment::query()
                ->whereDate('preferred_date', $parsedDate->toDateString())
                ->where('preferred_time', $time)
                ->whereIn('status', ['pending', 'confirmed'])
                ->exists()) {
                $validator->errors()->add('preferred_time', 'Ese horario acaba de ser reservado. Elige otro.');
            }
        })->validate();

        $validated['name'] = trim($validated['name']);
        $validated['email'] = strtolower(trim($validated['email']));
        $validated['phone'] = trim($validated['phone']);
        $validated['status'] = 'pending';
        do {
            $validated['confirmation_code'] = Str::upper(Str::random(8));
        } while (Appointment::where('confirmation_code', $validated['confirmation_code'])->exists());
        unset($validated['consent']);
        Appointment::create($validated);

        return redirect()->route('appointments.confirmation', $validated['confirmation_code']);
    }

    public function availability(Request $request): JsonResponse
    {
        $date = Carbon::parse($request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
        ])['date']);

        $slots = ['10:00', '12:00', '16:00', '18:00'];
        $booked = Appointment::query()
            ->whereDate('preferred_date', $date->toDateString())
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('preferred_time')
            ->map(fn ($time) => substr((string) $time, 0, 5))
            ->all();

        return response()->json([
            'date' => $date->toDateString(),
            'is_available' => $date->isWeekday(),
            'slots' => collect($slots)->map(fn ($slot) => [
                'time' => $slot,
                'available' => $date->isWeekday() && ! in_array($slot, $booked, true),
            ])->values(),
            'message' => $date->isWeekday() ? null : 'Las visitas guiadas están disponibles de lunes a viernes.',
        ]);
    }

    public function confirmation(string $code): View
    {
        $appointment = Appointment::where('confirmation_code', $code)->firstOrFail();

        return view('booking-confirmation', compact('appointment'));
    }
}
