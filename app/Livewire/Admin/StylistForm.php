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
    public $password_confirmation = '';
    public $current_password = '';

    #[On('open-stylist-form')]
    public function openModal(User $stylist = null)
    {
        $this->resetValidation();
        $this->reset(['name', 'email', 'phone', 'password', 'password_confirmation', 'current_password', 'userId']);

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
            // Al crear una nueva estilista, la contraseña y su confirmación son obligatorias
            $rules['password'] = 'required|min:8|confirmed';
        } else {
            // Al editar, si se rellena la nueva contraseña o la actual, se validan ambas
            if ($this->password || $this->current_password) {
                $rules['current_password'] = 'required';
                $rules['password'] = 'required|min:8|confirmed';
            }
        }

        $this->validate($rules);

        // Si estamos editando y se especificó cambio de contraseña, verificar la contraseña anterior
        if ($this->userId && $this->password) {
            $user = User::findOrFail($this->userId);
            if (!Hash::check($this->current_password, $user->password)) {
                $this->addError('current_password', 'La contraseña anterior es incorrecta.');
                return;
            }
        }

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user = User::updateOrCreate(['id' => $this->userId], $data);
        
        // Asignar el rol siempre al crear
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
