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
    public $description;
    public $image;
    public $start_date;
    public $finish_date;
    public $user_timezone = 'UTC';
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
        $this->initializeDates();
    }

    public function timezoneDetected()
    {
        $this->initializeDates();
    }

    private function initializeDates()
    {
        $this->start_date = now()
            ->timezone($this->user_timezone)
            ->addMinutes(10)
            ->format(Constants\Formats::DATE_TIME_FORMAT);
    }

    public function descriptionUpdated($content)
    {
        $this->description = $content;
    }

    public function save()
    {
        $this->validate();

        $this->title = Purifier::clean(
            $this->title,
            ['HTML.Allowed' => '']
        );
        $path = $this->image ? $this->image->store('games', 'public') : null;

        $startDateUtc = Carbon::parse($this->start_date, $this->user_timezone)->setTimezone('UTC');
        $finishDateUtc = Carbon::parse($this->finish_date, $this->user_timezone)->setTimezone('UTC');

        $game = Game::create([
            'title' => trim($this->title),
            'start_date' => $startDateUtc,
            'finish_date' => $finishDateUtc,
            'description' => Purifier::clean(
                $this->description,
                ['HTML.Allowed' => Constants\Html::ALLOWED_TAGS]
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
