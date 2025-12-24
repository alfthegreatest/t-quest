<?php

namespace App\Livewire;

use Livewire\Component;

class Timer extends Component
{
    public $startTimestamp;
    public $finishTimestamp;

    public function mount($startTimestamp = null, $finishTimestamp = null) {
        $this->startTimestamp = $startTimestamp;
        $this->finishTimestamp = $finishTimestamp;
    }

    public function render()
    {
        return view('livewire.timer');
    }
}