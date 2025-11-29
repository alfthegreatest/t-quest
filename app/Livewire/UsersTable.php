<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UsersTable extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.users-table', [
            'users' => User::paginate(20),
        ]);
    }
}
