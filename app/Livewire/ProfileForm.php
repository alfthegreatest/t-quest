<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProfileForm extends Component
{
    public $name;

    public $email;

    public $contact_telegram;

    public $contact_whatsapp;

    public $success;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->contact_telegram = $user->contact_telegram;
        $this->contact_whatsapp = $user->contact_whatsapp;
    }

    public function updated($field)
    {
        $user = Auth::user();

        $this->validateOnly($field, [
            'name' => 'required|min:3',
            'contact_telegram' => 'min:3',
            'contact_whatsapp' => 'min:3',
        ]);

        $user->update([
            $field => $this->$field,
        ]);

        if ($field === 'name') {
            $this->dispatch('profileNameUpdated', $this->name);
        }

        $this->dispatch($field);
    }

    public function render()
    {
        return view('livewire.profile-form');
    }
}
