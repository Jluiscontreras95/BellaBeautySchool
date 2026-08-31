<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['required', 'string', 'max:30'],
            'interest' => ['required', 'string', 'max:80'],
            'preferred_date' => ['required', 'date', 'after_or_equal:today'],
            'preferred_time' => ['required', 'in:'.implode(',', BookingService::slots())],
            'message' => ['required', 'string', 'max:500'],
            'consent' => ['required', 'accepted'],
        ]);

        $validated['name'] = trim($validated['name']);
        $validated['email'] = strtolower(trim($validated['email']));
        $validated['phone'] = trim($validated['phone']);
        unset($validated['consent']);

        try {
            $appointment = app(BookingService::class)->create($validated);
        } catch (ValidationException $exception) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($exception->errors());
        }

        return redirect()->route('appointments.confirmation', $appointment->confirmation_code);
    }

    public function availability(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        return response()->json(
            app(BookingService::class)->availabilityFor(Carbon::parse($validated['date']))
        );
    }

    public function confirmation(string $code): View
    {
        $appointment = Appointment::where('confirmation_code', $code)->firstOrFail();

        return view('booking-confirmation', compact('appointment'));
    }
}
