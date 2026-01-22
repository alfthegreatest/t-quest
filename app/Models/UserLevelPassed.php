<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Level;

class UserLevelPassed extends Model
{
    protected $table = 'user_level_passed';
    
    protected $fillable = [
        'user_id',
        'level_id',
        'passed',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function level()
    {
        return $this->belongsTo(Level::class);
    }
}