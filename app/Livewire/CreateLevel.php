<?php

namespace App\Livewire;

use App\Constants;
use App\Models\Game;
use App\Models\Level;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Mews\Purifier\Facades\Purifier;

class CreateLevel extends Component {

    public $showAddLevelModal = false;
    public $name;
    public $description;
    public $coordinates;
    
    public $availability_time_days = 0;
    public $availability_time_hours = 0;
    public $availability_time_minutes = 0;
    
    public $gameId; 
    
    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'description' => 'nullable|string',
            'availability_time_days' => 'required|integer|min:0|max:364',
            'availability_time_hours' => 'required|integer|min:0|max:23',
            'availability_time_minutes' => 'required|integer|min:0|max:59',
        ];
    }

    public function mount()
    {
        $this->gameId = request('game')->id;
    }

    // Computed property
    public function availabilityTime()
    {
        $days = (int) $this->availability_time_days;
        $hours = (int) $this->availability_time_hours;
        $minutes = (int) $this->availability_time_minutes;
        
        return ($days * 86400) + ($hours * 3600) + ($minutes * 60);
    }

    public function getFormattedTime()
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
        
        $totalSeconds = $this->availabilityTime();
        
        // Проверка диапазона
        if ($totalSeconds < 60) {
            $this->addError('availability_time', 'Time has to be one minute at least');
            return;
        }
        
        if ($totalSeconds > 31536000) {
            $this->addError('availability_time', 'Time cannot be grater than one minute');
            return;
        }
        
        $this->name = Purifier::clean($this->name, ['HTML.Allowed' => '']);

        $coordinates = DB::raw("ST_GeomFromText('POINT(55.751244 37.618423)', 4326)");

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

        $this->reset([
            'showAddLevelModal',
            'name', 
            'description', 
            'availability_time_days',
            'availability_time_hours',
            'availability_time_minutes'
        ]);
        
        $this->dispatch('refreshComponentLevelsList');
        $this->dispatch('toast', 'Level created!');
    }

    // live autovalidation
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.create-level');
    }
}