<?php

namespace App\Livewire;

use App\Models\Game;
use Livewire\Component;


class CreateLevel extends Component {

    public $showAddLevelModal = false;

    public function mount()
    {
    }

    public function render()
    {
        return view('livewire.create-level');
    }


}