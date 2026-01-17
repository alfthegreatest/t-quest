<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


class GameController extends Controller
{
    public function index()
    {
        return view('games.index');
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show(Game $game, Request $request)
    {
        $shareButtons = \Share::page($request->url(), $game->title, ['target' => '_blank'])
            ->facebook()
            ->x()
            ->telegram();

        $metaTitle = $game->title;
        $metaDescription = $game->description;
        $metaImage = asset('storage/' . $game->image);
        $metaUrl = $request->url();

        return view('games.show', compact(['game', 'shareButtons', 'metaTitle', 'metaDescription', 'metaImage', 'metaUrl']));
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function edit(Game $game)
    {
        return view('games.edit', compact('game'));
    }

    public function update(Request $request, Game $game)
    {
    }

    public function play(Game $game)
    {
        $cacheKey = "game.{$game->id}.levels";
    
        $levels = Cache::remember($cacheKey, 60, function () use ($game) {
            return $game->levels()
                ->selectRaw('
                    id,
                    game_id,
                    name,
                    description,
                    `order`,
                    availability_time,
                    ST_Y(coordinates) as lat,
                    ST_X(coordinates) as lng
                ')
                ->orderBy('order')
                ->get();
        });

        $locations = [];
        foreach($levels as $level) {
            $locations[] = [
                'name' => $level->name,
                'description' => $level->description,
                'lat' => $level->lat,
                'lng' => $level->lng
            ];
        }
    
        $game->setRelation('levels', $levels);
        $metaTitle = "{$game->title} - " . config('app.name');

        return view('games.play', compact('game', 'locations', 'metaTitle') );
    }

    public function destroy(Game $game)
    {
    }
}
