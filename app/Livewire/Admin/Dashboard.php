<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use Carbon\Carbon;

class Dashboard extends Component
{
    // ─── KPIs del Día ───
    public function getTodayEarningsProperty()
    {
        return Appointment::whereDate('scheduled_date', Carbon::today())
            ->whereIn('status', ['completed', 'realizada'])
            ->with('services')
            ->get()
            ->sum(fn($a) => $a->services->sum('price'));
    }

    public function getTodayAppointmentsProperty()
    {
        return Appointment::whereDate('scheduled_date', Carbon::today())->count();
    }

    public function getTodayCompletedProperty()
    {
        return Appointment::whereDate('scheduled_date', Carbon::today())
            ->whereIn('status', ['completed', 'realizada'])
            ->count();
    }

    public function getTodayNoShowsProperty()
    {
        return Appointment::whereDate('scheduled_date', Carbon::today())
            ->where('status', 'no_show')
            ->count();
    }

    public function getNewClientsWeekProperty()
    {
        return User::role('Cliente')
            ->where('created_at', '>=', Carbon::now()->startOfWeek())
            ->count();
    }

    // ─── Resumen Financiero ───
    public function getWeekEarningsProperty()
    {
        return Appointment::whereBetween('scheduled_date', [
                Carbon::now()->startOfWeek()->format('Y-m-d'),
                Carbon::now()->endOfWeek()->format('Y-m-d'),
            ])
            ->whereIn('status', ['completed', 'realizada'])
            ->with('services')
            ->get()
            ->sum(fn($a) => $a->services->sum('price'));
    }

    public function getLastWeekEarningsProperty()
    {
        return Appointment::whereBetween('scheduled_date', [
                Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d'),
                Carbon::now()->subWeek()->endOfWeek()->format('Y-m-d'),
            ])
            ->whereIn('status', ['completed', 'realizada'])
            ->with('services')
            ->get()
            ->sum(fn($a) => $a->services->sum('price'));
    }

    public function getMonthEarningsProperty()
    {
        return Appointment::whereMonth('scheduled_date', Carbon::now()->month)
            ->whereYear('scheduled_date', Carbon::now()->year)
            ->whereIn('status', ['completed', 'realizada'])
            ->with('services')
            ->get()
            ->sum(fn($a) => $a->services->sum('price'));
    }

    public function getWeekGrowthProperty()
    {
        if ($this->lastWeekEarnings == 0) return $this->weekEarnings > 0 ? 100 : 0;
        return round((($this->weekEarnings - $this->lastWeekEarnings) / $this->lastWeekEarnings) * 100, 1);
    }

    // ─── Top Servicios ───
    public function getTopServicesProperty()
    {
        return Service::withCount(['appointments' => function ($query) {
                $query->whereMonth('scheduled_date', Carbon::now()->month)
                      ->whereYear('scheduled_date', Carbon::now()->year);
            }])
            ->orderByDesc('appointments_count')
            ->limit(5)
            ->get();
    }

    // ─── Actividad Reciente ───
    public function getRecentAppointmentsProperty()
    {
        return Appointment::with(['client', 'specialist.user', 'services'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();
    }

    // ─── Citas de Hoy (Timeline) ───
    public function getTodayTimelineProperty()
    {
        return Appointment::whereDate('scheduled_date', Carbon::today())
            ->with(['client', 'specialist.user', 'services'])
            ->orderBy('time', 'asc')
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
