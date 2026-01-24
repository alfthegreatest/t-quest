<?php

namespace App\Livewire;

use App\Models\Code;
use Livewire\Component;

class CodesList extends Component
{
    protected $listeners = ['refreshComponentCodeList' => '$refresh'];

    public function render()
    {
        return view(
            'livewire.codes-list', 
            ['codes' => Code::orderBy('id', 'desc')->paginate(10)]
        );
    }
}
