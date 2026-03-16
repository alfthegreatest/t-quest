<?php

namespace App\Livewire;

use App\Models\Code;
use App\Models\Level;
use App\Models\UserGameCompleted;
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
        $userId = auth()->id();
        $code = strtolower(trim($this->code));
        $codeExists = Code::where('level_id', $this->levelId)
            ->where('code', $code)
            ->exists();

        if ($codeExists) {
            $this->code = '';

            UserLevelPassed::where('user_id', $userId)
                ->where('level_id', $this->levelId)
                ->update(['passed' => 1]);

            $this->dispatch('level-completed', levelId: $this->levelId);
            $this->dispatch('toast', 'Success! Level completed.');
        } else {
            $this->dispatch('toast', 'Wrong code');
        }

        $gameId = Level::where('id', $this->levelId)->value('game_id');
        $levelsIDs = Level::where('game_id', $gameId)->pluck('id');
        $notPassedCount = UserLevelPassed::where('user_id', $userId)
            ->whereIn('level_id', $levelsIDs)
            ->where('passed', 0)
            ->count();

        if ($notPassedCount === 0) {
            UserGameCompleted::firstOrCreate([
                'user_id' => $userId,
                'game_id' => $gameId,
            ]);
            $this->dispatch('toast', 'All levels completed.');
            $this->redirect(route('game.finish', $gameId));
        }
    }

    public function render()
    {
        return view('livewire.enter-code-field');
    }
}