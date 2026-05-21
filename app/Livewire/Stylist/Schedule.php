<?php

namespace App\Livewire\Stylist;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\Specialist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Schedule extends Component
{
    public $selectedDate;
    public $weekStart;
    public $monthStart;
    public $viewMode = 'week'; // 'week' o 'month'

    public function mount()
    {
        $this->selectedDate = Carbon::today()->format('Y-m-d');
        $this->weekStart = Carbon::today()->startOfWeek()->format('Y-m-d');
        $this->monthStart = Carbon::today()->startOfMonth()->format('Y-m-d');
    }

    public function getSpecialistProperty()
    {
        return Specialist::where('user_id', Auth::id())->first();
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
        if ($mode === 'week') {
            $this->weekStart = Carbon::parse($this->selectedDate)->startOfWeek()->format('Y-m-d');
        } else {
            $this->monthStart = Carbon::parse($this->selectedDate)->startOfMonth()->format('Y-m-d');
        }
    }

    // Devuelve los 7 días de la semana y carga TODAS las citas de esa semana
    public function getWeeklyDataProperty()
    {
        $days = [];
        $start = Carbon::parse($this->weekStart);
        $end = $start->copy()->addDays(6);
        
        $appointments = collect();
        if ($this->specialist) {
            $appointments = Appointment::where('specialist_id', $this->specialist->id)
                ->whereBetween('scheduled_date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->whereIn('status', ['pending', 'confirmed', 'approved'])
                ->with(['client', 'services'])
                ->orderBy('time', 'asc')
                ->get();
        }

        // Agrupar citas por fecha
        $groupedAppointments = $appointments->groupBy('scheduled_date');

        for ($i = 0; $i < 7; $i++) {
            $currentDate = $start->copy()->addDays($i);
            $dateString = $currentDate->format('Y-m-d');
            
            $days[] = [
                'date' => $dateString,
                'dayName' => $currentDate->isoFormat('dddd'),
                'dayNumber' => $currentDate->format('d'),
                'isToday' => $currentDate->isToday(),
                'appointments' => $groupedAppointments->get($dateString, collect()),
            ];
        }
        return $days;
    }

    // Calcula todos los días para la cuadrícula mensual y agrupa citas
    public function getMonthlyDataProperty()
    {
        $days = [];
        $startOfMonth = Carbon::parse($this->monthStart);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        $daysInMonth = $startOfMonth->daysInMonth;
        
        $appointments = collect();
        if ($this->specialist) {
            $appointments = Appointment::where('specialist_id', $this->specialist->id)
                ->whereBetween('scheduled_date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
                ->whereIn('status', ['pending', 'confirmed', 'approved'])
                ->with(['client', 'services'])
                ->orderBy('time', 'asc')
                ->get();
        }

        $groupedAppointments = $appointments->groupBy('scheduled_date');

        $firstDayOfWeek = $startOfMonth->dayOfWeekIso; 

        // Relleno previo
        for ($i = 1; $i < $firstDayOfWeek; $i++) {
            $days[] = [
                'isPadding' => true,
            ];
        }

        // Días reales del mes
        for ($i = 0; $i < $daysInMonth; $i++) {
            $currentDate = $startOfMonth->copy()->addDays($i);
            $dateString = $currentDate->format('Y-m-d');

            $days[] = [
                'isPadding' => false,
                'date' => $dateString,
                'dayNumber' => $currentDate->format('d'),
                'isToday' => $currentDate->isToday(),
                'appointments' => $groupedAppointments->get($dateString, collect()),
            ];
        }

        return $days;
    }

    // Citas para el detalle inferior (en caso de que sigan queriendo ver detalles al hacer clic)
    public function getSelectedAppointmentsProperty()
    {
        if (!$this->specialist) return collect();

        return Appointment::where('specialist_id', $this->specialist->id)
            ->whereDate('scheduled_date', $this->selectedDate)
            ->with(['client', 'services'])
            ->orderBy('time', 'asc')
            ->get();
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
    }

    public function previousPeriod()
    {
        if ($this->viewMode === 'week') {
            $this->weekStart = Carbon::parse($this->weekStart)->subWeek()->format('Y-m-d');
            $this->selectedDate = $this->weekStart;
        } else {
            $this->monthStart = Carbon::parse($this->monthStart)->subMonth()->format('Y-m-d');
            $this->selectedDate = $this->monthStart;
        }
    }

    public function nextPeriod()
    {
        if ($this->viewMode === 'week') {
            $this->weekStart = Carbon::parse($this->weekStart)->addWeek()->format('Y-m-d');
            $this->selectedDate = $this->weekStart;
        } else {
            $this->monthStart = Carbon::parse($this->monthStart)->addMonth()->format('Y-m-d');
            $this->selectedDate = $this->monthStart;
        }
    }

    public function goToToday()
    {
        $this->selectedDate = Carbon::today()->format('Y-m-d');
        if ($this->viewMode === 'week') {
            $this->weekStart = Carbon::today()->startOfWeek()->format('Y-m-d');
        } else {
            $this->monthStart = Carbon::today()->startOfMonth()->format('Y-m-d');
        }
    }

    public function render()
    {
        $periodTitle = $this->viewMode === 'week' 
            ? Carbon::parse($this->weekStart)->isoFormat('MMMM YYYY') 
            : Carbon::parse($this->monthStart)->isoFormat('MMMM YYYY');

        return view('livewire.stylist.schedule', [
            'weeklyData' => $this->weeklyData,
            'monthlyData' => $this->monthlyData,
            'selectedAppointments' => $this->selectedAppointments,
            'periodTitle' => $periodTitle,
        ]);
    }
}
