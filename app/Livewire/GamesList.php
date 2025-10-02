<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Game;
use App\Livewire\GameManager;
use Illuminate\Support\Facades\Storage;
use App\Livewire\Traits\WithImageValidation;

class GamesList extends Component
{
    use WithPagination;
    use WithImageValidation;
    use \Livewire\WithFileUploads;


    protected $listeners = ['refreshComponentGameList' => '$refresh'];

    public $showModal = false;
    public $showEditGameModal = false;
    public $gameId, $title, $description, $image, $imagePath;

    protected $rules = [
        'title' => 'required|string|min:3|max:255',
        'description' => 'nullable|string',
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

        if (!$game)
            return;

        if ($game->image)
            Storage::disk('public')->delete($game->image);

        $game->delete();
        $this->showModal = false;
        $this->dispatch('toast', 'Game deleted');
    }

    public function edit($id)
    {
        $game = Game::find($id);
        if (!$game) return;

        $this->fill([
            'gameId' => $game->id,
            'title' => $game->title,
            'description' => $game->description,
            'image' => null,
            'imagePath' => $game->image,
        ]);

        $this->showEditGameModal = true;
    }

    public function render()
    {
        return view('livewire.games-list', [
            'games' => Game::orderBy('id', 'desc')->paginate(10),
        ]);
    }

    public function update()
    {
        $rules = $this->rules;
        $rules['image'] = $this->image instanceof \Livewire\TemporaryUploadedFile
            ? 'image|max:2048'
            : 'nullable';

        $this->validate($rules);

        $game = Game::find($this->gameId);
        if (!$game) return;

        $game->title = $this->title;
        $game->description = $this->description;

        // Если выбрали новый файл → сохранить и обновить путь
        if ($this->image) {
            if ($this->imagePath)
                Storage::disk('public')->delete($this->imagePath);

            $path = $this->image->store('games', 'public');
            $game->image = $path;
        }

        $game->save();

        $this->reset(['title', 'description', 'image', 'imagePath', 'gameId', 'showEditGameModal']);
        $this->dispatch('toast', 'Changes saved');
    }

    // live autovalidation
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatedImage()
    {
        $this->validateOnly('image');
    }
}
