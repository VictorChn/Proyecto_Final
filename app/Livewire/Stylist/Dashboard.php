<?php

namespace App\Livewire\Stylist;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\Specialist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $todayDate;

    public function mount()
    {
        $this->todayDate = Carbon::today()->format('Y-m-d');
    }

    public function getSpecialistProperty()
    {
        return Specialist::where('user_id', Auth::id())->first();
    }

    public function getTodayAppointmentsProperty()
    {
        if (!$this->specialist) return collect();

        return Appointment::where('specialist_id', $this->specialist->id)
            ->where('scheduled_date', $this->todayDate)
            ->with(['client', 'services'])
            ->orderBy('time', 'asc')
            ->get();
    }

    public function getKpisProperty()
    {
        $appointments = $this->todayAppointments;
        
        return [
            'total' => $appointments->count(),
            'pending' => $appointments->where('status', 'pending')->count() + $appointments->where('status', 'confirmed')->count(),
            'completed' => $appointments->whereIn('status', ['completed', 'realizada'])->count(),
            'no_show' => $appointments->where('status', 'no_show')->count(),
        ];
    }

    public function markAsCompleted($appointmentId)
    {
        $appointment = Appointment::find($appointmentId);
        
        if ($appointment && $appointment->specialist_id == $this->specialist->id) {
            $appointment->status = 'completed';
            $appointment->save();
            
            $this->dispatch('swal:success', title: '¡Excelente!', text: 'La cita ha sido marcada como completada. El cliente ya puede descargar su ticket.');
        }
    }

    public function markAsNoShow($appointmentId)
    {
        $appointment = Appointment::find($appointmentId);
        
        if ($appointment && $appointment->specialist_id == $this->specialist->id) {
            $appointment->status = 'no_show';
            $appointment->save();
            
            $this->dispatch('swal:warning', title: 'Registro actualizado', text: 'Se ha marcado la cita como Inasistencia (No-Show).');
        }
    }

    public function render()
    {
        return view('livewire.stylist.dashboard', [
            'appointments' => $this->todayAppointments,
            'kpis' => $this->kpis,
        ]);
    }
}
