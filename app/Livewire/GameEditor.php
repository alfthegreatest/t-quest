<?php

namespace App\Livewire;

use Mews\Purifier\Facades\Purifier;
use App\Models\Game;
use Livewire\Component;
use Carbon\Carbon;

class GameEditor extends Component
{
    const DATE_TIME_FORMAT = 'Y-m-d\TH:i';
    public Game $game;

    public $title;
    public $description;
    public $start_date;
    public $finish_date;


    public function mount(Game $game)
    {
        $this->title = $game->title;
        $this->description = $game->description;

        $this->start_date = $game->start_date
            ? $game->start_date
            : now()->addMinutes(5)->format(self::DATE_TIME_FORMAT);

        $this->finish_date = $game->finish_date
            ? $game->finish_date
            : now()->addDays(7)->format(self::DATE_TIME_FORMAT);
    }

    public function updated()
    {
        $this->game->update([
            'title' => $this->title,
            'description' => Purifier::clean(
                $this->description,
                [
                    'HTML.Allowed' => \App\Constants\Html::ALLOWED_TAGS,
                ]
            ),
        ]);
    }

    public function updatedStartDate($value)
    {
        $this->game->start_date = Carbon::createFromFormat(self::DATE_TIME_FORMAT, $value);
        $this->game->save();
    }

    public function updatedFinishDate($value)
    {
        $this->game->finish_date = Carbon::createFromFormat(self::DATE_TIME_FORMAT, $value);
        $this->game->save();
    }

    public function render()
    {
        return view('livewire.game-editor');
    }
}
