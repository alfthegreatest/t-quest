<?php

namespace App\Livewire;

use App\Models\Location;
use Livewire\Component;
use Livewire\WithPagination;

class LocationsList extends Component
{
    use WithPagination;

    protected $listeners = ['refreshComponentLocationsList' => '$refresh'];
    public $showModal = false;
    public $locationId;
    public $locationTitle;
    public $title;

    protected $rules = [
        'title' => 'required|string|min:3|max:255',
    ];

    public function confirmDelete($id, $title)
    {
        $this->locationId = $id;
        $this->locationTitle = $title;
        $this->showModal = true;
    }

    public function delete()
    {
        $location = Location::find($this->locationId);

        if (!$location) {
            return;
        }

        $location->delete();
        $this->showModal = false;
        $this->dispatch('toast', 'Location deleted');
    }

     public function render()
    {
        return view('livewire.locations-list', [
            'locations' => Location::orderBy('title', 'asc')->paginate(10),
        ]);
    }

    public function update()
    {
        $rules = $this->rules;
        $this->validate($rules);

        $location = Game::find($this->locationId);
        if (!$location) {
            return;
        }

        $location->title = Purifier::clean(
            $this->title,
            ['HTML.Allowed' => '']
        );
        $location->title = trim($location->title);

        $location->description = Purifier::clean(
            $this->description,
            ['HTML.Allowed' => \App\Constants\Html::ALLOWED_TAGS]
        );
        $location->save();

        $this->reset(['title']);
        $this->dispatch('toast', 'Changes saved');
    }

    // live autovalidation
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
}
