<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AccountName extends Component
{
    public $name;

    protected $listeners = ['profileNameUpdated' => 'setProfileName'];

    public function mount()
    {
        $this->name = Auth::user()->name;
    }

    public function setProfileName($newName)
    {
        $this->name = $newName;
    }

    public function render()
    {

        return view('livewire.account-name');
    }
}
