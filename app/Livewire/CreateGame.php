<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Game;
use App\Livewire\Traits\WithImageValidation;


class CreateGame extends Component
{
    use \Livewire\WithFileUploads;
    use WithImageValidation;

    public $showAddGameModal = false;
    public $title, $description, $image;

    protected $rules = [
        'title' => 'required|string|min:3|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:2048',
    ];

    protected $listeners = ['descriptionUpdated'];

    public function descriptionUpdated($content)
    {
        $this->description = $content;
    }


    public function save()
    {
        $this->validate();

        $path = $this->image ? $this->image->store('games', 'public') : null;

        $game = Game::create([
            'title' => $this->title,
            'description' => $this->description,
            'image' => $path,
            'created_by' => auth()->id(),
        ]);

        $this->reset(['title', 'description', 'image', 'showAddGameModal']);
        $this->dispatch('refreshComponentGameList');
        $this->dispatch('toast', 'Game created successfully!');
    }

    // live autovalidation
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function canPreview($file)
    {
        return $file && in_array($file->getClientOriginalExtension(), ['jpg','jpeg','png','gif','bmp','svg','webp']);
    }

    public function render()
    {
        return view('livewire.create-game');
    }
}