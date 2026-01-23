<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Level;
use Livewire\Attributes\Computed;


class EditLevel extends Component
{
    protected $listeners = ['showEditLevelPopup' => 'showPopup'];

    public $showEditLevelPopup = false;
    public $showDeleteLevelButtons = false;
    public $showMapModal = false;

    public $id;
    public $name;
    public $description;
    public $points;
    public $latitude;
    public $longitude;

    public $level;
    
    public $availability_time_days = 0;
    public $availability_time_hours = 0;
    public $availability_time_minutes = 0;

    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'description' => 'nullable|string',
            'points' => 'required|integer|min:0',
            'availability_time_minutes' => 'integer|min:0|max:59',
            'availability_time_hours' => 'integer|min:0|max:23',
            'availability_time_days' => 'integer|min:0|max:364',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ];
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

    public function showPopup($id = null, $name = null)
    {
        $this->id = $id;
        $this->name = $name;
        
        $this->level = Level::find($this->id);
        $this->description = $this->level->description;
        $this->points = $this->level->points;
        $this->latitude = $this->level->latitude;
        $this->longitude = $this->level->longitude;

        $totalSeconds = $this->level->availability_time ?? 0;
        $this->availability_time_days = floor($totalSeconds / 86400);
        $this->availability_time_hours = floor(($totalSeconds % 86400) / 3600);
        $this->availability_time_minutes = floor(($totalSeconds % 3600) / 60);

        $this->showEditLevelPopup = true;
    }

    public function deleteLevel($id) {
        Level::destroy($id);
        $this->dispatch('refreshComponentLevelsList');
        $this->dispatch('toast', "Level deleted");

        $this->reset();
    }

    public function updatedName($value) {
        $this->validateOnly('name');
        $this->level->update(['name' => trim($value)]);
        $this->dispatch('name');
    }

    public function updatedDescription($value) {
        $this->validateOnly('description');
        $this->level->update(['description' => trim($value)]);
        $this->dispatch('description');
    }

    public function updatedPoints($value) {
        $this->validateOnly('points');
        $this->level->update(['points' => $value]);
        $this->dispatch('points');
    }

    public function updatedAvailabilityTimeDays($value) {
        $this->validateOnly('availability_time_days');
        $this->dispatch('availability_time');
        $this->level->update(['availability_time' => $this->availabilityTime]);
    }

    public function updatedAvailabilityTimeHours($value) {
        $this->validateOnly('availability_time_hours');
        $this->dispatch('availability_time');
        $this->level->update(['availability_time' => $this->availabilityTime]);
    }

    public function updatedAvailabilityTimeMinutes($value) {
        $this->validateOnly('availability_time_minutes');
        $this->dispatch('availability_time');
        $this->level->update(['availability_time' => $this->availabilityTime]);
    }

    public function updatedLongitude($value) {
        $this->validateOnly('longitude');
        $this->updateCoordinates();
    }

    public function updatedLatitude($value) {
        $this->validateOnly('latitude');
        $this->updateCoordinates();
    }

    private function updateCoordinates() {
        if ($this->latitude === null || $this->longitude === null)
            return;

        $this->dispatch('coordinates');
        $this->level->update([
            'coordinates' => [
                'lat' => $this->latitude,
                'lng' => $this->longitude,
            ]
        ]);
    }

    public function clearCoordinates()
    {
        $this->latitude = null;
        $this->longitude = null;
        $this->showMapModal = false;
    }

    public function render()
    {
        return view('livewire.edit-level', ['availabilityTimeFormatted' => $this->availabilityTimeFormatted]);
    }
}