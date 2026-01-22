<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\UserLevelPassed;
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
        $userId = auth()->id();

        $levels = $game->levels()->selectRaw('
            levels.id,
            levels.game_id,
            levels.name,
            levels.description,
            levels.`order`,
            levels.availability_time,
            ST_Y(levels.coordinates) as lat,
            ST_X(levels.coordinates) as lng,
            COALESCE(user_level_passed.passed, 0) as passed
        ')
        ->leftJoin('user_level_passed', function($join) use ($userId) {
            $join->on('levels.id', '=', 'user_level_passed.level_id')
                ->where('user_level_passed.user_id', '=', $userId);
        })
        ->orderBy('levels.order')
        ->get();
        


        $levelIds = $levels->pluck('id')->toArray();
        $exists = UserLevelPassed::where('user_id', $userId)
            ->whereIn('level_id', $levelIds)
            ->exists();

        if ( !$exists ) {
            $records = [];
            $now = now();
            
            foreach ($levelIds as $levelId) {
                $records[] = [
                    'user_id' => $userId,
                    'level_id' => $levelId,
                    'passed' => false,
                    'created_at' => $now,
                    'updated_at' => $now,    
                ];
            }
            
            UserLevelPassed::insert($records);
        }    
        
        $locations = [];
        foreach($levels as $level) {
            $locations[] = [
                'name' => $level->name,
                'description' => $level->description,
                'passed' => $level->passed,
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
