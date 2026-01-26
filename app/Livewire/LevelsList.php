<?php

namespace App\Livewire;

use App\Models\Level;
use Livewire\Component;


class LevelsList extends Component
{
    protected $listeners = ['refreshComponentLevelsList' => '$refresh'];
    public $gameId;
    public $levels;

    public function mount($gameId)
    {
        $this->gameId = $gameId;
    }

    public function render()
    {
        $this->levels = Level::query()
            ->where('game_id', $this->gameId)
            ->orderBy('order', 'asc')
            ->select('*')
            ->selectRaw('ST_X(coordinates) as longitude')
            ->selectRaw('ST_Y(coordinates) as latitude')
            ->get();
            
        return view('livewire.levels-list');
    }

}
