<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Game;

class UserGameCompleted extends Model
{
    protected $table = 'user_game_completed';

    protected $fillable = [
        'user_id',
        'game_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}