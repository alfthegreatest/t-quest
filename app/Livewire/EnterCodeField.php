<?php

namespace App\Livewire;

use Livewire\Component;

class EnterCodeField extends Component
{
    public $code = '';
    public ?int $levelId = null;

    protected $listeners = [
        'level-changed' => 'setLevel',
    ];

    public function setLevel($levelId)
    {
        $this->levelId = (int) $levelId;
    }

    public function enterCode()
    {
        
    }

    public function render()
    {
        return view('livewire.enter-code-field');
    }
}
