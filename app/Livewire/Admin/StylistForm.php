<?php

namespace App\Livewire\Admin;

use Livewire\Component;

use App\Models\User;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Hash;

class StylistForm extends Component
{
    public $showModal = false;
    public $userId;
    public $name = '';
    public $email = '';
    public $phone = '';
    public $password = '';

    #[On('open-stylist-form')]
    public function openModal(User $stylist = null)
    {
        $this->resetValidation();
        $this->reset(['name', 'email', 'phone', 'password', 'userId']);

        if ($stylist && $stylist->id) {
            $this->userId = $stylist->id;
            $this->name = $stylist->name;
            $this->email = $stylist->email;
            $this->phone = $stylist->phone;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'phone' => 'nullable|string|max:20',
        ];

        if (!$this->userId) {
            $rules['password'] = 'required|min:8';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user = User::updateOrCreate(['id' => $this->userId], $data);
        
        // Asignar el rol siempre
        if (!$this->userId) {
            $user->assignRole('Estilista');
        }

        $this->showModal = false;
        $this->dispatch('stylist-saved');
        $this->dispatch('swal:success', title: '¡Guardado!', text: 'Los datos de la estilista han sido guardados correctamente.');
    }

    public function render()
    {
        return view('livewire.admin.stylist-form');
    }
}
