<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Level;

class LevelController extends Controller
{
    public function index(Game $game, Level $level)
    {
        $title = "$game->title - $level->name";
        $levelId = $level->id;

        return view('levels.index', compact(['title', 'levelId']));
    }
}
