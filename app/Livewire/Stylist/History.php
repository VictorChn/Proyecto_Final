<?php

namespace App\Livewire\Stylist;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\Specialist;
use Illuminate\Support\Facades\Auth;

class History extends Component
{
    public function getSpecialistProperty()
    {
        return Specialist::where('user_id', Auth::id())->first();
    }

    public function getHistoryProperty()
    {
        if (!$this->specialist) return collect();

        // Fetch completed or no_show appointments
        return Appointment::where('specialist_id', $this->specialist->id)
            ->whereIn('status', ['completed', 'realizada', 'no_show'])
            ->with(['client', 'services'])
            ->orderBy('scheduled_date', 'desc')
            ->orderBy('time', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.stylist.history', [
            'appointments' => $this->history,
        ]);
    }
}
