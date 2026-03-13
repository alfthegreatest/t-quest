<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GameStatusMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $now = now();

        if (!(bool) $request->game->active || $request->game->finish_date < $now) {
            return redirect()->route('game.finish', ['game' => $request->game->id]);
        } elseif ($request->game->start_date > $now) {
            return redirect()->route('game.detail', ['game' => $request->game->id]);
        }

        return $next($request);
    }
}
