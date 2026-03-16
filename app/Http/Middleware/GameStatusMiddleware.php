<?php

namespace App\Http\Middleware;

use App\Models\UserGameCompleted;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class GameStatusMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $now = now();
        $userId = auth()->id();
        $gameFinished = $request->game->finish_date < $now;
        $gameCompleted = UserGameCompleted::where('user_id', $userId)
            ->where('game_id', $request->game->id)
            ->exists();

        if (
            !(bool) $request->game->active
            || $gameFinished
            || $gameCompleted
        ) {
            return redirect()->route('game.finish', ['game' => $request->game->id]);
        } elseif ($request->game->start_date > $now) {
            return redirect()->route('game.detail', ['game' => $request->game->id]);
        }

        return $next($request);
    }
}
