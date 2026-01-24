<?php

namespace App\Livewire;

use App\Models\Code;
use Livewire\Component;
use Mews\Purifier\Facades\Purifier;

class AddCode extends Component
{
    public $levelId;
    public $code;

    protected $rules = [
        'code' => 'required|string|max:255',
        'levelId' => 'required|integer|exists:levels,id'
    ];

    public function mount(?int $levelId = null)
    {
        $this->levelId = $levelId;
    }

    public function save()
    {
        $this->validate();
        $this->code = Purifier::clean(
            $this->code,
            ['HTML.Allowed' => '']
        );

        $code = Code::create([
            'level_id' => $this->levelId,
            'code' => trim($this->code),
        ]);

        $this->reset(['code']);
        $this->dispatch('refreshComponentCodeList');
        $this->dispatch('toast', 'Code added');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.add-code');
    }
}
