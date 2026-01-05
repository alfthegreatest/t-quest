<?php

namespace App\Livewire;

use Mews\Purifier\Facades\Purifier;
use App\Livewire\Traits\WithImageValidation;
use App\Models\Game;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class GamesList extends Component
{
    use \Livewire\WithFileUploads;
    use WithImageValidation;
    use WithPagination;

    protected $listeners = ['refreshComponentGameList' => '$refresh'];
    public $showModal = false;
    public $active = false;
    public $gameId;
    public $title;
    public $description;
    public $image;
    public $imagePath;

    protected $rules = [
        'title' => 'required|string|min:3|max:255',
        'description' => 'nullable|string',
        'active' => 'boolean',
        'image' => 'nullable|image|max:2048',
    ];

    public function confirmDelete($id)
    {
        $this->gameId = $id;
        $this->showModal = true;
    }

    public function delete()
    {
        $game = Game::find($this->gameId);

        if (!$game) {
            return;
        }

        if ($game->image) {
            Storage::disk('public')->delete($game->image);
        }

        $game->delete();
        $this->showModal = false;
        $this->dispatch('toast', 'Game deleted');
    }

    public function render()
    {
        return view('livewire.games-list', [
            'games' => Game::orderBy('id', 'desc')->paginate(10),
        ]);
    }
}
