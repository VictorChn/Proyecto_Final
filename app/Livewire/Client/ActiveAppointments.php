<?php

namespace App\Livewire\Client;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\Specialist;
use App\Mail\AppointmentRescheduled;
use App\Mail\AppointmentCancelled;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\On;

class ActiveAppointments extends Component
{
    public $isRescheduling = false;
    public $reschedulingAppointmentId = null;
    public $selectedDate = '';
    public $selectedTime = '';

    // Stripe Success Polling Properties
    public $showPaymentModal = false;
    public $paymentIntentId = null;
    public $paymentStatus = 'processing'; // 'processing', 'succeeded', 'failed', 'timeout'
    public $paidAppointment = null;
    public $pollAttempts = 0;

    public function mount()
    {
        $this->selectedDate = Carbon::today()->format('Y-m-d');

        // Check if redirected from Stripe success
        $paymentIntent = request()->query('payment_intent');
        $redirectStatus = request()->query('redirect_status');

        if ($paymentIntent && $redirectStatus === 'succeeded') {
            $this->showPaymentModal = true;
            $this->paymentIntentId = $paymentIntent;
            $this->paymentStatus = 'processing';
            $this->pollAttempts = 0;

            // Run initial check
            $this->checkPaymentStatus();
        }
    }

    public function checkPaymentStatus()
    {
        if (!$this->paymentIntentId) {
            return;
        }

        $this->pollAttempts++;

        // Find the appointment with this Stripe PaymentIntent ID
        $appointment = Appointment::where('stripe_session_id', $this->paymentIntentId)
            ->with(['specialist.user', 'services'])
            ->first();

        if ($appointment) {
            if ($appointment->payment_status === 'paid' && $appointment->status === 'confirmed') {
                $this->paymentStatus = 'succeeded';
                $this->paidAppointment = $appointment;
            } elseif ($this->pollAttempts > 15) {
                // If it takes more than 15 attempts (~22 seconds), show timeout with action button
                $this->paymentStatus = 'timeout';
            }
        } else {
            if ($this->pollAttempts > 15) {
                $this->paymentStatus = 'failed';
            }
        }
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->paymentIntentId = null;
        $this->paymentStatus = 'processing';
        $this->paidAppointment = null;

        return redirect()->route('dashboard');
    }

    public function render()
    {
        $user = Auth::user();
        
        $activeAppointments = Appointment::where('client_id', $user->id)
            ->whereNotIn('status', ['completed', 'realizada', 'cancelled', 'cancelada'])
            ->with(['specialist.user', 'services'])
            ->orderBy('scheduled_date', 'asc')
            ->orderBy('time', 'asc')
            ->get();

        return view('livewire.client.active-appointments', compact('activeAppointments'));
    }

    public function confirmCancel($id)
    {
        $appointment = Appointment::findOrFail($id);

        // Check 24 hour limit in backend to prevent manual payload injection
        $appointmentDateTime = Carbon::parse($appointment->scheduled_date . ' ' . $appointment->time);
        if (Carbon::now()->diffInHours($appointmentDateTime, false) < 24) {
            $this->dispatch('swal:error', title: 'Acción Bloqueada', text: 'No puedes cancelar una cita con menos de 24 horas de anticipación.');
            return;
        }

        $this->dispatch('swal:confirm-cancel-appointment', id: $id);
    }

    #[On('cancel-appointment-confirmed')]
    public function cancelAppointment($id)
    {
        $id = $id['id'] ?? $id;
        $appointment = Appointment::with(['client', 'specialist.user', 'services'])->findOrFail($id);

        // Check 24 hour limit in backend
        $appointmentDateTime = Carbon::parse($appointment->scheduled_date . ' ' . $appointment->time);
        if (Carbon::now()->diffInHours($appointmentDateTime, false) < 24) {
            $this->dispatch('swal:error', title: 'Acción Bloqueada', text: 'No puedes cancelar una cita con menos de 24 horas de anticipación.');
            return;
        }

        $appointment->status = 'cancelled';
        $appointment->save();

        // Send confirmation email to client
        try {
            Mail::to($appointment->client->email)->send(new AppointmentCancelled($appointment, 'client'));
        } catch (\Exception $e) {
            logger('Error sending appointment cancellation email to client: ' . $e->getMessage());
        }

        // Send notification email to stylist
        try {
            if ($appointment->specialist && $appointment->specialist->user) {
                Mail::to($appointment->specialist->user->email)->send(new AppointmentCancelled($appointment, 'stylist'));
            }
        } catch (\Exception $e) {
            logger('Error sending appointment cancellation email to stylist: ' . $e->getMessage());
        }

        // Enviar notificaciones push a la Estilista y al Administrador
        try {
            $dateFormatted = Carbon::parse($appointment->scheduled_date)->format('d/m/Y');
            $timeFormatted = Carbon::parse($appointment->time)->format('g:i A');

            // 1. A la Estilista
            if ($appointment->specialist && $appointment->specialist->user) {
                \App\Services\PushNotificationService::send(
                    $appointment->specialist->user,
                    'Cita Cancelada 🗑️',
                    "El cliente {$appointment->client->name} canceló su cita del {$dateFormatted} a las {$timeFormatted}.",
                    '/dashboard'
                );
            }

            // 2. Al Administrador
            \App\Services\PushNotificationService::sendToRole(
                'Administrador',
                'Cita Cancelada 🗑️',
                "El cliente {$appointment->client->name} canceló su cita del {$dateFormatted} a las {$timeFormatted}.",
                '/dashboard'
            );
        } catch (\Exception $e) {
            logger('Error al enviar notificaciones push de cancelación: ' . $e->getMessage());
        }

        $this->dispatch('swal:success', title: '¡Cita Cancelada!', text: 'Tu cita ha sido cancelada correctamente y se ha notificado por correo.');
    }

    public function startReschedule($id)
    {
        $appointment = Appointment::findOrFail($id);

        // Check 24 hour limit
        $appointmentDateTime = Carbon::parse($appointment->scheduled_date . ' ' . $appointment->time);
        if (Carbon::now()->diffInHours($appointmentDateTime, false) < 24) {
            $this->dispatch('swal:error', title: 'Acción Bloqueada', text: 'No puedes reagendar una cita con menos de 24 horas de anticipación.');
            return;
        }

        $this->reschedulingAppointmentId = $id;
        $this->selectedDate = $appointment->scheduled_date;
        $this->selectedTime = ''; // Clear time selection so they must choose a new one
        $this->isRescheduling = true;
    }

    public function closeReschedule()
    {
        $this->isRescheduling = false;
        $this->reschedulingAppointmentId = null;
        $this->selectedTime = '';
    }

    public function getTimeSlotsProperty()
    {
        if (!$this->selectedDate || !$this->reschedulingAppointmentId) {
            return [];
        }

        $date = Carbon::parse($this->selectedDate);

        // Salon schedule is Monday to Friday only
        if ($date->isWeekend()) {
            return [];
        }

        $appointment = Appointment::with('services')->find($this->reschedulingAppointmentId);
        if (!$appointment) {
            return [];
        }

        $specialist = $appointment->specialist;
        if (!$specialist) {
            return [];
        }

        $totalDuration = $appointment->services->sum('duration') ?: 30;

        // Retrieve existing non-cancelled appointments for this specialist on selected date
        // Exclude the current appointment itself from this check!
        $existingAppointments = Appointment::where('specialist_id', $specialist->id)
            ->where('scheduled_date', $this->selectedDate)
            ->where('status', '!=', 'cancelled')
            ->where('id', '!=', $this->reschedulingAppointmentId)
            ->with('services')
            ->get();

        $busyRanges = [];
        foreach ($existingAppointments as $app) {
            $startApp = Carbon::parse($this->selectedDate . ' ' . $app->time);
            $durationApp = $app->services->sum('duration');
            $endApp = $startApp->copy()->addMinutes($durationApp);
            $busyRanges[] = [
                'start' => $startApp,
                'end' => $endApp,
            ];
        }

        $slots = [];
        $start = Carbon::createFromTime(8, 0, 0); // 8:00 AM
        $end = Carbon::createFromTime(17, 0, 0); // 5:00 PM

        while ($start->copy()->addMinutes($totalDuration)->lte($end)) {
            $slotTime = $start->format('H:i');
            $slotStart = Carbon::parse($this->selectedDate . ' ' . $start->format('H:i:s'));
            $slotEnd = $slotStart->copy()->addMinutes($totalDuration);

            $isAvailable = true;

            // Check if this slot overlaps with any busy ranges
            foreach ($busyRanges as $busy) {
                if ($slotStart->lt($busy['end']) && $slotEnd->gt($busy['start'])) {
                    $isAvailable = false;
                    break;
                }
            }

            // If the date is today, check that the slot time is in the future
            if ($date->isToday()) {
                $slotDateTime = Carbon::parse($this->selectedDate . ' ' . $slotTime);
                if ($slotDateTime->lt(Carbon::now())) {
                    $isAvailable = false;
                }
            }

            $slots[] = [
                'time' => $slotTime,
                'available' => $isAvailable,
            ];

            $start->addMinutes(30); // 30-minute interval slots
        }

        return $slots;
    }

    public function updatedSelectedDate($value)
    {
        $this->selectedTime = '';
        if ($value) {
            $date = Carbon::parse($value);
            if ($date->isWeekend()) {
                $this->addError('selectedDate', 'La estética labora únicamente de lunes a viernes.');
            } else {
                $this->resetErrorBag('selectedDate');
            }
        }
    }

    public function saveReschedule()
    {
        $this->validate([
            'selectedDate' => 'required|date',
            'selectedTime' => 'required',
        ], [
            'selectedDate.required' => 'La fecha es obligatoria.',
            'selectedTime.required' => 'Debes seleccionar una hora.',
        ]);

        $appointment = Appointment::with(['client', 'specialist.user', 'services'])->findOrFail($this->reschedulingAppointmentId);

        // Check 24 hour limit
        $appointmentDateTime = Carbon::parse($appointment->scheduled_date . ' ' . $appointment->time);
        if (Carbon::now()->diffInHours($appointmentDateTime, false) < 24) {
            $this->dispatch('swal:error', title: 'Acción Bloqueada', text: 'No puedes reagendar una cita con menos de 24 horas de anticipación.');
            return;
        }

        // Verify if slot is available again to prevent race conditions
        $availableSlots = collect($this->timeSlots);
        $selectedSlot = $availableSlots->firstWhere('time', $this->selectedTime);
        if (!$selectedSlot || !$selectedSlot['available']) {
            $this->addError('selectedTime', 'Este horario ya no está disponible.');
            return;
        }

        // Update appointment scheduled date and time
        $appointment->scheduled_date = $this->selectedDate;
        $appointment->time = $this->selectedTime;
        $appointment->status = 'pending'; // Reset to pending after reschedule to be approved again
        $appointment->save();

        // Send email confirmation of rescheduled appointment
        try {
            Mail::to($appointment->client->email)->send(new AppointmentRescheduled($appointment));
        } catch (\Exception $e) {
            logger('Error sending appointment reschedule email: ' . $e->getMessage());
        }

        // Enviar notificaciones push a la Estilista y al Administrador
        try {
            $dateFormatted = Carbon::parse($appointment->scheduled_date)->format('d/m/Y');
            $timeFormatted = Carbon::parse($appointment->time)->format('g:i A');

            // 1. A la Estilista
            if ($appointment->specialist && $appointment->specialist->user) {
                \App\Services\PushNotificationService::send(
                    $appointment->specialist->user,
                    'Cita Reagendada ⏰',
                    "El cliente {$appointment->client->name} reagendó su cita para el {$dateFormatted} a las {$timeFormatted}.",
                    '/dashboard'
                );
            }

            // 2. Al Administrador
            \App\Services\PushNotificationService::sendToRole(
                'Administrador',
                'Cita Reagendada ⏰',
                "El cliente {$appointment->client->name} reagendó su cita para el {$dateFormatted} a las {$timeFormatted}.",
                '/dashboard'
            );
        } catch (\Exception $e) {
            logger('Error al enviar notificaciones push de reprogramación: ' . $e->getMessage());
        }

        $this->isRescheduling = false;
        $this->reschedulingAppointmentId = null;

        $this->dispatch('swal:success', title: '¡Cita Reagendada!', text: 'Tu cita ha sido reprogramada con éxito. Se ha enviado un correo de confirmación.');
    }
}
