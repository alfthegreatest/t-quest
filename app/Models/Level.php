<?php

namespace App\Models;

use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class Level extends Model
{
    protected $fillable = [
        'order',
        'game_id',
        'name',
        'description',
        'points',
        'coordinates',
        'availability_time',
    ];

    protected $hidden = ['coordinates'];
    protected $appends = ['latitude', 'longitude'];

    public function setCoordinatesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['coordinates'] = DB::raw(
                "ST_GeomFromText('POINT({$value['lng']} {$value['lat']})', 4326)"
            );
        } else {
            $this->attributes['coordinates'] = $value;
        }
    }

    public function getLatitudeAttribute()
    {
        return $this->getCoordsPart('lat');
    }

    public function getLongitudeAttribute()
    {
        return $this->getCoordsPart('lng');
    }

    private function getCoordsPart($part)
    {
        $value = $this->getRawOriginal('coordinates');
    
        if (!$value) {
            return null;
        }

        try {
            $result = DB::table('levels')
                ->selectRaw('ST_Y(coordinates) as lat, ST_X(coordinates) as lng')
                ->where('id', $this->id)
                ->first();
            
            return $part === 'lat' ? (float) $result->lat : (float) $result->lng;
        } catch (\Exception $e) {
            \Log::error('Coordinates parsing error: ' . $e->getMessage());
            return null;
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($level) {
            Cache::forget("game.{$level->game_id}.levels");
        });
        
        static::deleted(function ($level) {
            Cache::forget("game.{$level->game_id}.levels");
        });

        static::creating(function ($level) {
            if (is_null($level->order)) {
                $maxOrder = static::where('game_id', $level->game_id)
                    ->max('order') ?? 0;
                $level->order = $maxOrder + 1;
            }
        });
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function usersPassed()
    {
        return $this->belongsToMany(User::class, 'user_level_passed')
                    ->withTimestamps();
    }

}