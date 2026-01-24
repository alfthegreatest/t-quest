<?php

namespace App\Livewire;

use App\Models\Code;
use App\Models\UserLevelPassed;

use Livewire\Component;

class EnterCodeField extends Component
{
    public $code = '';
    public ?int $levelId = null;

    protected $listeners = [
        'level-changed' => 'setLevel',
    ];

    public function setLevel($levelId)
    {
        $this->levelId = (int) $levelId;
    }

    public function enterCode()
    {
        $code = strtolower(trim($this->code));
        $codeExists = Code::where('level_id', $this->levelId)
            ->where('code', $code)
            ->exists();

        if ($codeExists) {
            $this->code = '';

            $userId = auth()->id();
            UserLevelPassed::where('user_id', $userId)
                ->where('level_id', $this->levelId)
                ->update(['passed' => 1]);
                
            $this->dispatch('level-completed', levelId: $this->levelId);
            $this->dispatch('toast', 'Success! Level completed.');
        } else {
            $this->dispatch('toast', 'Wrong code');
        }
    }

    public function render()
    {
        return view('livewire.enter-code-field');
    }
}