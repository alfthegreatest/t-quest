<?php

namespace App\Livewire;

use Mews\Purifier\Facades\Purifier;
use App\Models\Game;
use Livewire\Component;
use Carbon\Carbon;
use App\Constants;
use Illuminate\Support\Facades\Storage;
use App\Livewire\Traits\WithImageValidation;
use Livewire\WithFileUploads;

class GameEditor extends Component
{
    use WithImageValidation;
    use WithFileUploads;
    public Game $game;
    public $title;
    public $description;
    public $image;
    public $imagePath;
    public $start_date;
    public $finish_date;
    public $user_timezone = 'UTC';

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'nullable|image|max:2048',
        'start_date' => 'required|date',
        'finish_date' => 'required|date|after:start_date',
    ];

    public function mount(Game $game)
    {
        $this->title = $game->title;
        $this->description = $game->description;
        $this->image = null;
        $this->imagePath = $game->image;
        $this->initializeDates();
    }

    private function initializeDates()
    {
        $this->start_date = $this->game->start_date->timezone($this->user_timezone)->format(Constants\Formats::DATE_TIME_FORMAT);
        $this->finish_date = $this->game->finish_date->timezone($this->user_timezone)->format(Constants\Formats::DATE_TIME_FORMAT);
    }

    public function timezoneDetected()
    {
        $this->initializeDates();
    }

    public function updated()
    {
        $this->title = Purifier::clean(
            $this->title,
            ['HTML.Allowed' => '']
        );

        $this->game->update([
            'title' => trim($this->title),
            'description' => Purifier::clean(
                $this->description,
                ['HTML.Allowed' => Constants\Html::ALLOWED_TAGS]
            ),
        ]);
    }

    public function updatedImage($value)
    {
        $rules['image'] = $this->image instanceof \Livewire\TemporaryUploadedFile
            ? 'image|max:2048'
            : 'nullable';

        $this->validate($rules);

        if ($this->game->image) {
            Storage::disk('public')->delete($this->game->image);
        }

        $path = $this->image->store('games', 'public');
        $this->game->image = $path;
        $this->game->save();

        $this->imagePath = $path;
        $this->dispatch('image');
    }

    public function removeImage()
    {
        if ($this->game->image) {
            Storage::disk('public')->delete($this->game->image);
        }

        $this->image = null;
        $this->imagePath = null;
        $this->game->image = null;
        $this->game->save();

        $this->dispatch('toast', 'Image removed.');
    }

    public function updatedTitle($value)
    {
        $this->dispatch('title');
    }

    public function updatedDescription($value)
    {
        $this->dispatch('description');
    }

    public function updatedStartDate($value)
    {
        $this->game->start_date = Carbon::parse($value, $this->user_timezone)->setTimezone('UTC');
        $this->game->save();
        $this->dispatch('start_date');
    }

    public function updatedFinishDate($value)
    {
        $this->game->finish_date = Carbon::parse($value, $this->user_timezone)->setTimezone('UTC');
        $this->game->save();
        $this->dispatch('finish_date');
    }


    public function getImageUrlProperty()
    {
        if ($this->image instanceof \Livewire\TemporaryUploadedFile) {
            return $this->image->temporaryUrl();
        }

        if ($this->game?->image) {
            return asset('storage/' . $this->game->image);
        }

        return null;
    }

    public function render()
    {
        return view('livewire.game-editor');
    }
}
