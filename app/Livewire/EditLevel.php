<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Level;


class EditLevel extends Component
{
    protected $listeners = ['showEditLevelPopup' => 'showPopup'];

    public $showEditLevelPopup = false;
    public $id;
    public $name;
    public $level;


    public function showPopup($id = null, $name = null)
    {
        $this->id = $id;
        $this->name = $name;
        
        $this->level = Level::find($this->id);
        $this->showEditLevelPopup = true;
    }

    function save() {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        $level = Level::find($this->id);
        
        if ($level) {
            $level->name = $this->name;
            $level->save();
            
            $this->showEditLevelPopup = false;
            //$this->dispatch('levelUpdated');
        }
    }

    function render()
    {
        return view('livewire.edit-level');
    }
}