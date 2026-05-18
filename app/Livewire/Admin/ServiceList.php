<?php

namespace App\Livewire\Admin;

use Livewire\Component;

use App\Models\Service;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class ServiceList extends Component
{
    use WithPagination;

    #[On('service-saved')]
    public function render()
    {
        $services = Service::paginate(10);
        
        return view('livewire.admin.service-list', [
            'services' => $services
        ]);
    }

    public function createService()
    {
        $this->dispatch('open-service-form');
    }

    public function editService(Service $service)
    {
        $this->dispatch('open-service-form', $service);
    }

    public function confirmDelete($id)
    {
        $this->dispatch('swal:confirm-delete-service', id: $id);
    }

    #[On('delete-service-confirmed')]
    public function deleteService($id)
    {
        $service = Service::find($id['id'] ?? $id);
        if ($service) {
            $service->delete();
            $this->dispatch('swal:success', title: '¡Eliminado!', text: 'El servicio ha sido eliminado correctamente.');
        }
    }
}
