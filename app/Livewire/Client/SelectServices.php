<?php

namespace App\Livewire\Client;

use Livewire\Component;
use App\Models\Service;
use App\Models\User;
use App\Models\Specialist;
use App\Models\Appointment;
use App\Mail\AppointmentConfirmed;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SelectServices extends Component
{
    public $step = 1;
    public $search = '';
    public $selectedCategory = '';
    public $cart = []; // Array storing selected service IDs
    public $selectedSpecialistId = '';
    public $selectedDate = '';
    public $selectedTime = '';
    
    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategory' => ['except' => ''],
    ];

    public function mount()
    {
        // Set default date to today or tomorrow if today is weekend
        $today = Carbon::today();
        if ($today->isWeekend()) {
            $this->selectedDate = $today->next(Carbon::MONDAY)->format('Y-m-d');
        } else {
            $this->selectedDate = $today->format('Y-m-d');
        }
    }

    public function getCategoriesProperty()
    {
        return Service::select('category')->distinct()->pluck('category')->filter()->toArray();
    }

    public function getServicesProperty()
    {
        $query = Service::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        if ($this->selectedCategory) {
            $query->where('category', $this->selectedCategory);
        }

        return $query->get();
    }

    public function getSpecialistsProperty()
    {
        // Get all users who have the role "Estilista"
        return User::role('Estilista')->get();
    }

    public function toggleService($serviceId)
    {
        if (in_array($serviceId, $this->cart)) {
            $this->cart = array_diff($this->cart, [$serviceId]);
        } else {
            $this->cart[] = $serviceId;
        }
    }

    public function getSelectedServicesModelsProperty()
    {
        return Service::whereIn('id', $this->cart)->get();
    }

    public function getTotalPriceProperty()
    {
        return $this->selectedServicesModels->sum('price');
    }

    public function getTotalDurationProperty()
    {
        return $this->selectedServicesModels->sum('duration');
    }

    public function getFormattedDurationProperty()
    {
        $minutes = $this->totalDuration;
        if ($minutes < 60) {
            return $minutes . ' min';
        }
        $hours = floor($minutes / 60);
        $remMinutes = $minutes % 60;
        return $hours . 'h' . ($remMinutes > 0 ? ' ' . $remMinutes . 'min' : '');
    }

    public function getTimeSlotsProperty()
    {
        if (!$this->selectedDate) {
            return [];
        }

        $date = Carbon::parse($this->selectedDate);
        
        // Schedule is Monday to Friday only
        if ($date->isWeekend()) {
            return [];
        }

        // Fetch the stylist's Specialist record
        $specialist = null;
        if ($this->selectedSpecialistId) {
            $specialist = Specialist::where('user_id', $this->selectedSpecialistId)->first();
        }

        // Retrieve existing non-cancelled appointments for this specialist on selected date
        $busyRanges = [];
        if ($specialist) {
            $existingAppointments = Appointment::where('specialist_id', $specialist->id)
                ->where('scheduled_date', $this->selectedDate)
                ->where('status', '!=', 'cancelled')
                ->with('services')
                ->get();

            foreach ($existingAppointments as $app) {
                $startApp = Carbon::parse($this->selectedDate . ' ' . $app->time);
                $durationApp = $app->services->sum('duration');
                $endApp = $startApp->copy()->addMinutes($durationApp);
                $busyRanges[] = [
                    'start' => $startApp,
                    'end' => $endApp,
                ];
            }
        }

        $slots = [];
        $start = Carbon::createFromTime(8, 0, 0); // 8:00 AM
        $end = Carbon::createFromTime(17, 0, 0); // 5:00 PM
        $newDuration = $this->totalDuration ?: 30; // New treatment duration

        while ($start->copy()->addMinutes($newDuration)->lte($end)) {
            $slotTime = $start->format('H:i');
            $slotStart = Carbon::parse($this->selectedDate . ' ' . $start->format('H:i:s'));
            $slotEnd = $slotStart->copy()->addMinutes($newDuration);
            
            $isAvailable = true;

            // Check if this slot overlaps with any busy ranges
            foreach ($busyRanges as $busy) {
                if ($slotStart->lt($busy['end']) && $slotEnd->gt($busy['start'])) {
                    $isAvailable = false;
                    break;
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

    public function updatedSelectedSpecialistId()
    {
        $this->selectedTime = '';
    }

    public function nextStep()
    {
        if ($this->step === 1) {
            if (empty($this->cart)) {
                $this->dispatch('swal:error', title: '¡Atención!', text: 'Debes seleccionar al menos un servicio para tu cita.');
                return;
            }
            $this->step = 2;
        } elseif ($this->step === 2) {
            $this->validate([
                'selectedSpecialistId' => 'required',
                'selectedDate' => 'required|date',
                'selectedTime' => 'required',
            ], [
                'selectedSpecialistId.required' => 'Debes seleccionar una estilista.',
                'selectedDate.required' => 'Debes seleccionar una fecha.',
                'selectedTime.required' => 'Debes seleccionar una hora.',
            ]);

            $date = Carbon::parse($this->selectedDate);
            if ($date->isWeekend()) {
                $this->addError('selectedDate', 'La estética labora únicamente de lunes a viernes.');
                return;
            }

            // Verify if the selected time is actually available
            $availableSlots = collect($this->timeSlots);
            $selectedSlot = $availableSlots->firstWhere('time', $this->selectedTime);
            if (!$selectedSlot || !$selectedSlot['available']) {
                $this->addError('selectedTime', 'Este horario ya no está disponible con la estilista seleccionada.');
                return;
            }

            $this->step = 3;
        }
    }

    public function previousStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function saveAppointment()
    {
        $this->validate([
            'selectedSpecialistId' => 'required',
            'selectedDate' => 'required|date',
            'selectedTime' => 'required',
        ]);

        // Verify if slot is available again to prevent race conditions
        $availableSlots = collect($this->timeSlots);
        $selectedSlot = $availableSlots->firstWhere('time', $this->selectedTime);
        if (!$selectedSlot || !$selectedSlot['available']) {
            $this->addError('selectedTime', 'Este horario ya no está disponible con la estilista seleccionada.');
            return;
        }

        // Get the stylist User
        $stylistUser = User::findOrFail($this->selectedSpecialistId);

        // Ensure a Specialist record exists in database to satisfy the foreign key constraint
        $specialist = Specialist::firstOrCreate(
            ['user_id' => $stylistUser->id],
            [
                'specialty' => 'Estilista General',
                'active' => true
            ]
        );

        // Create the appointment
        $appointment = Appointment::create([
            'client_id' => Auth::id(),
            'specialist_id' => $specialist->id,
            'scheduled_date' => $this->selectedDate,
            'time' => $this->selectedTime,
            'status' => 'pending',
        ]);

        // Associate selected services in pivot table
        $appointment->services()->attach($this->cart);

        // Eager load relations for the email
        $appointment->load(['client', 'services']);

        // Send confirmation email
        try {
            Mail::to(Auth::user()->email)->send(new AppointmentConfirmed($appointment));
        } catch (\Exception $e) {
            // Log or ignore email failures in local environments
            logger('Error sending appointment confirmation email: ' . $e->getMessage());
        }

        // Success Alert and redirect
        $this->dispatch('swal:success', title: '¡Cita Agendada!', text: 'Tu cita ha sido programada con éxito. Se ha enviado un correo de confirmación.');
        
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.client.select-services', [
            'services' => $this->services,
            'categories' => $this->categories,
            'specialists' => $this->specialists,
            'totalPrice' => $this->totalPrice,
            'formattedDuration' => $this->formattedDuration,
            'timeSlots' => $this->timeSlots,
            'selectedServices' => $this->selectedServicesModels,
        ]);
    }
}
