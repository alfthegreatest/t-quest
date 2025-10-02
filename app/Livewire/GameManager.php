<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Game;

class GameManager extends Component
{
    public $games;

    public function mount() {
        $this->games = Game::orderBy('id', 'desc')->get();
    }

    public function render() {
        return view('livewire.game-manager');
    }
}