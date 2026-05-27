<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Setting;

class Settings extends Component
{
    public $notification_hour;

    protected $rules = [
        'notification_hour' => 'required|string|regex:/^\d{2}:\d{2}$/',
    ];

    protected $messages = [
        'notification_hour.required' => 'El horario de envío es obligatorio.',
        'notification_hour.regex' => 'El formato del horario no es válido (debe ser HH:MM).',
    ];

    public function mount()
    {
        $val = Setting::getVal('notification_hour', '19:00');
        // Retrocompatibilidad con enteros antiguos (ej: "19")
        if (strpos($val, ':') === false) {
            $val = sprintf('%02d:00', (int)$val);
        }
        $this->notification_hour = $val;
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
