<?php

namespace App\Livewire;

use Mews\Purifier\Facades\Purifier;
use App\Livewire\Traits\WithImageValidation;
use App\Models\Game;
use Livewire\Component;
use Carbon\Carbon;
use App\Constants;


class CreateGame extends Component
{
    use \Livewire\WithFileUploads;
    use WithImageValidation;

    public $showAddGameModal = false;

    public $title;

    public $start_date;

    public $finish_date;

    public $description;

    public $image;

    protected $rules = [
        'title' => 'required|string|min:3|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:2048',
        'start_date' => 'required|date',
        'finish_date' => 'required|date|after:start_date',
    ];

    protected $listeners = ['descriptionUpdated'];

    public function mount()
    {
        $this->start_date = now()->addMinutes(10)->format(Constants\Formats::DATE_TIME_FORMAT);
    }

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
            'start_date' => Carbon::parse($this->start_date),
            'finish_date' => Carbon::parse($this->finish_date),
            'description' => Purifier::clean(
                $this->description,
                [
                    'HTML.Allowed' => Constants\Html::ALLOWED_TAGS,
                ]
            ),
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
        return $file && in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp']);
    }

    public function render()
    {
        return view('livewire.create-game');
    }
}
