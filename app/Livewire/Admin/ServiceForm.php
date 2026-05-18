<?php

namespace App\Livewire\Admin;

use Livewire\Component;

use App\Models\Service;
use Livewire\Attributes\On;

class ServiceForm extends Component
{
    public $showModal = false;
    public $serviceId;
    public $name = '';
    public $description = '';
    public $category = '';
    public $price = '';
    public $duration = '';

    #[On('open-service-form')]
    public function openModal(Service $service = null)
    {
        $this->resetValidation();
        $this->reset(['name', 'description', 'category', 'price', 'duration', 'serviceId']);

        if ($service && $service->id) {
            $this->serviceId = $service->id;
            $this->name = $service->name;
            $this->description = $service->description;
            $this->category = $service->category;
            $this->price = $service->price;
            $this->duration = $service->duration;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
        ]);

        Service::updateOrCreate(
            ['id' => $this->serviceId],
            [
                'name' => $this->name,
                'description' => $this->description,
                'category' => $this->category,
                'price' => $this->price,
                'duration' => $this->duration,
            ]
        );

        $this->showModal = false;
        $this->dispatch('service-saved');
        $this->dispatch('swal:success', title: '¡Guardado!', text: 'El servicio ha sido guardado exitosamente en el catálogo.');
    }

    public function render()
    {
        return view('livewire.admin.service-form');
    }
}
