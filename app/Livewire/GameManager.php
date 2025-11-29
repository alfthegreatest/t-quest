<?php

namespace App\Livewire;

use App\Models\Game;
use Livewire\Component;

class GameManager extends Component
{
    public $games;

    public function mount()
    {
        $this->games = Game::where('active', true)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.game-manager');
    }
}
