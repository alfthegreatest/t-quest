<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

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