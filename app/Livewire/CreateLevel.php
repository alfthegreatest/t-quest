<?php

namespace App\Livewire;

use App\Constants;
use App\Models\Game;
use App\Models\Level;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Mews\Purifier\Facades\Purifier;


class CreateLevel extends Component
{

    public $showAddLevelModal = false;
    public $showMapModal = false;
    public $name;
    public $description;
    public $latitude;
    public $longitude;
    
    public $availability_time_days = 0;
    public $availability_time_hours = 0;
    public $availability_time_minutes = 0;
    
    public $gameId; 
    
    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'description' => 'nullable|string',
            'availability_time_minutes' => 'integer|min:0|max:59',
            'availability_time_hours' => 'integer|min:0|max:23',
            'availability_time_days' => 'integer|min:0|max:364',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ];
    }

    public function clearCoordinates()
    {
        $this->latitude = null;
        $this->longitude = null;
        $this->showMapModal = false;
    }

    public function mount($gameId)
    {
        $this->gameId = $gameId;
    }

    #[Computed]
    public function availabilityTime(): int
    {
        $days = (int) $this->availability_time_days;
        $hours = (int) $this->availability_time_hours;
        $minutes = (int) $this->availability_time_minutes;
        
        return ($days * 86400) + ($hours * 3600) + ($minutes * 60);
    }

    #[Computed]
    public function availabilityTimeFormatted(): string
    {
        $parts = [];
        
        if ($this->availability_time_days > 0) {
            $parts[] = $this->availability_time_days . ' ' . 
                ($this->availability_time_days == 1 ? 'day' : 'days');
        }
        
        if ($this->availability_time_hours > 0) {
            $parts[] = $this->availability_time_hours . ' ' . 
                ($this->availability_time_hours == 1 ? 'hour' : 'hours');
        }
        
        if ($this->availability_time_minutes > 0) {
            $parts[] = $this->availability_time_minutes . ' ' . 
                ($this->availability_time_minutes == 1 ? 'minute' : 'minutes');
        }
        
        return implode(', ', $parts);
    }

    public function save()
    {
        $this->validate();
        
        if ($this->availabilityTime <= 0) {
            $this->addError('availability_time', 'Please specify at least some time.');
            return;
        }

        $totalSeconds = $this->availabilityTime;
        $coordinates = DB::raw(sprintf(
            'ST_GeomFromText("POINT(%F %F)", 4326)',
            $this->longitude,
            $this->latitude
        ));

        Level::create([
            'name' => trim($this->name),
            'description' => Purifier::clean(
                $this->description,
                ['HTML.Allowed' => Constants\Html::ALLOWED_TAGS]
            ),
            'game_id' => $this->gameId,
            'coordinates' => $coordinates,
            'availability_time' => $totalSeconds,
        ]);

        $this->resetExcept('gameId');
        $this->dispatch('refreshComponentLevelsList');
        $this->dispatch('toast', 'Level created!');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function cancelMapSelection()
    {
        $this->showMapModal = false;
    }

    public function render()
    {
        return view('livewire.create-level', ['availabilityTimeFormatted' => $this->availabilityTimeFormatted]);
    }
}