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
    public $location_id;
    public $locations;
    public $start_date;
    public $finish_date;
    public $user_timezone = 'UTC';

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'nullable|image|max:2048',
        'location_id' => 'nullable',
        'start_date' => 'required|date',
        'finish_date' => 'required|date|after:start_date',
    ];

    public function mount(Game $game)
    {
        $this->title = $game->title;
        $this->description = $game->description;
        $this->image = null;
        $this->imagePath = $game->image;
        $this->location_id = $game->location_id;
        $this->locations = \App\Models\Location::orderBy('title')->get();
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

    public function updatedImage($value)
    {
        $rules['image'] = $this->image instanceof \Livewire\TemporaryUploadedFile
            ? 'image|max:2048'
            : 'nullable';

        $this->validate($rules);

        DB::transaction(function () {
            if ($this->game->image) {
                Storage::disk('public')->delete($this->game->image);
            }

            $this->game->update([
                'image' => $this->image->store('games', 'public')
            ]);
        });

        $this->imagePath = $this->game->image;
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
        $this->title = Purifier::clean(
            $value,
            ['HTML.Allowed' => '']
        );
        $this->game->update(['title' => trim($this->title)]);
        $this->dispatch('title');
    }

    public function updatedDescription($value)
    {
        $this->description = Purifier::clean(
            $value,
            ['HTML.Allowed' => Constants\Html::ALLOWED_TAGS]
        );
        $this->game->update(['description' => trim($this->description)]);
        $this->dispatch('description');
    }

    public function updatedStartDate($value)
    {
        try {
            $this->game->update([
                'start_date' => Carbon::parse($value, $this->user_timezone)->setTimezone($this->user_timezone)
            ]);
            $this->dispatch('start_date');
        } catch (\Exception $e) {
            $this->addError('start_date', 'Invalid date format');
        }
    }

    public function updatedFinishDate($value)
    {
        try {
            $this->game->update([
                'finish_date' => Carbon::parse($value, $this->user_timezone)->setTimezone($this->user_timezone)
            ]);
            $this->dispatch('finish_date');
        } catch (\Exception $e) {
            $this->addError('finish_date', 'Invalid date format');
        }
    }

    public function updatedLocationId($value)
    {
        $this->game->update(['location_id' => $value ?: null]);
        $this->dispatch('location_id');
    }

    public function getImageUrlProperty()
    {
        if ($this->image instanceof \Livewire\TemporaryUploadedFile) {
            return $this->image->temporaryUrl();
        }

        return $this->game?->image
            ? asset('storage/' . $this->game->image)
            : null;
    }

    public function render()
    {
        return view('livewire.game-editor');
    }
}
