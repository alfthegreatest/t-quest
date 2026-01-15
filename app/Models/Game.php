<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Location;
use App\Models\Level;


class Game extends Model
{
    /** @use HasFactory<\Database\Factories\GameFactory> */
    use HasFactory;

    protected $table = 'games';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'image',
        'location',
        'location_id',
        'start_date',
        'finish_date',
        'created_by',
        'active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'start_date' => 'datetime',
            'finish_date' => 'datetime',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    protected function isInProgress(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->start_date || !$this->finish_date) {
                    return false;
                }
                
                return $this->start_date->timestamp < now()->timestamp 
                    && $this->finish_date->timestamp > now()->timestamp;
            }
        );
    }

    public function levels()
    {
        return $this->hasMany(Level::class);
    }
}
