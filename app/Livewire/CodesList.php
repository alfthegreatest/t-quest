<?php

namespace App\Livewire;

use App\Models\Code;
use Livewire\Component;

class CodesList extends Component
{
    public $levelId;

    protected $listeners = ['refreshComponentCodeList' => '$refresh'];

    public function mount(?int $levelId = null)
    {
        $this->levelId = $levelId;
    }

    public function render()
    {
        return view(
            'livewire.codes-list', 
            ['codes' => Code::where('level_id', $this->levelId)->orderBy('id', 'desc')->paginate(10)]
        );
    }
}
