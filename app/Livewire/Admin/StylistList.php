<?php

namespace App\Livewire\Admin;

use Livewire\Component;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class StylistList extends Component
{
    use WithPagination;

    #[On('stylist-saved')]
    public function render()
    {
        $stylists = User::role('Estilista')->paginate(10);
        
        return view('livewire.admin.stylist-list', [
            'stylists' => $stylists
        ]);
    }

    public function createStylist()
    {
        $this->dispatch('open-stylist-form');
    }

    public function editStylist(User $stylist)
    {
        $this->dispatch('open-stylist-form', $stylist);
    }

    public function confirmDelete($id)
    {
        $this->dispatch('swal:confirm-delete-stylist', id: $id);
    }

    #[On('delete-stylist-confirmed')]
    public function deleteStylist($id)
    {
        $stylist = User::find($id['id'] ?? $id);
        if ($stylist) {
            $stylist->delete();
            $this->dispatch('swal:success', title: '¡Eliminada!', text: 'La estilista ha sido eliminada correctamente.');
        }
    }
}
