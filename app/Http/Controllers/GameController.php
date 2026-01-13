<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

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

    public function play()
    {
        return view('games.play');
    }

    public function destroy(Game $game)
    {
    }
}
