<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ProfileForm extends Component
{
    public $name;
    public $success;

    public function mount()
    {
        $this->name = Auth::user()->name;
    }

    public function updated($field)
    {
        $user = Auth::user();

        $this->validateOnly($field, [
            'name' => 'required|min:3',
        ]);

        $user->update([
            $field => $this->$field,
        ]);

        $this->dispatch('profileNameUpdated', $this->name);
        $this->dispatch('show-success');
    }

    public function render()
    {
        return view('livewire.profile-form');
    }
}
