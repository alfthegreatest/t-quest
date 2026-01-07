<?php

namespace App\Livewire;

use App\Models\Level;
use Livewire\Component;


class LevelsList extends Component
{
    protected $listeners = ['refreshComponentLevelsList' => '$refresh'];
    public $showModal = false;
    public $name;
    public $gameId;

    protected $rules = [
        'name' => 'required|string|min:1|max:255',
    ];

    public function mount($gameId)
    {
        $this->gameId = $gameId;
    }

    public function render()
    {
        return view('livewire.levels-list', [
            //'levels' => Level::where('game_id', $this->gameId)->orderBy('order', 'asc')->get(),
        ]);
    }
}
