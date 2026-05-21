<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Setting;

class Settings extends Component
{
    public $notification_hour;

    protected $rules = [
        'notification_hour' => 'required|integer|between:0,23',
    ];

    protected $messages = [
        'notification_hour.required' => 'La hora de envío es obligatoria.',
        'notification_hour.integer' => 'La hora debe ser un número entero.',
        'notification_hour.between' => 'La hora debe estar entre las 00:00 y las 23:00.',
    ];

    public function mount()
    {
        $this->notification_hour = (int) Setting::getVal('notification_hour', 19);
    }

    public function save()
    {
        $this->validate();

        Setting::setVal('notification_hour', $this->notification_hour);

        session()->flash('message', 'Configuración guardada exitosamente.');
    }

    public function render()
    {
        return view('livewire.admin.settings');
    }
}
