<?php

namespace App\Livewire;

use App\Models\Game;
use Livewire\Component;

class GameManager extends Component
{
    public $games;

    public function mount()
    {
        $currentDate = now();
        $this->games = Game::where('active', true)
            ->orderByRaw("
                CASE
                    WHEN start_date <= ? AND finish_date >= ? THEN 1
                    WHEN start_date > ? THEN 2
                    WHEN finish_date < ? THEN 3
                END
            ", [$currentDate, $currentDate, $currentDate, $currentDate])
            ->orderBy('start_date', 'asc')
            ->get();
    }

    public function render()
    {
        return view('livewire.game-manager');
    }
}
