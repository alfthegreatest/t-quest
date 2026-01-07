<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Location;
use Mews\Purifier\Facades\Purifier;


class CreateLocation extends Component
{
    public $showAddLocationModal = false;
    public $title;
    protected $rules = [
        'title' => 'required|string|min:3|max:255|unique:locations,title',
    ];

    public function mount()
    {
    }

    public function save()
    {
        $this->validate();
        $this->title = Purifier::clean(
            $this->title,
            ['HTML.Allowed' => '']
        );

        $location = Location::create([
            'title' => trim($this->title),
        ]);

        $this->reset(['title', 'showAddLocationModal']);
        $this->dispatch('refreshComponentLocationsList');
        $this->dispatch('toast', 'Created successfully!');
    }

    // live autovalidation
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.create-location');
    }
}
