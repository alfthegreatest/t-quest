<?php

namespace App\Models;

use App\Models\Level;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;


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
        'base_location',
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
    protected $hidden = ['base_location'];
    protected $appends = ['latitude', 'longitude'];
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

    public function users()
    {
        return $this->hasMany(User::class);
    }


    public function setBaseLocationAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['base_location'] = DB::raw(
                "ST_GeomFromText('POINT({$value['lng']} {$value['lat']})', 4326)"
            );
        } else {
            $this->attributes['base_location'] = $value;
        }
    }

    public function getLatitudeAttribute()
    {
        return $this->getBaseLocationPart('lat');
    }

    public function getLongitudeAttribute()
    {
        return $this->getBaseLocationPart('lng');
    }

    private function getBaseLocationPart($part)
    {
        $value = $this->getRawOriginal('base_location');

        if (!$value) {
            return null;
        }

        try {
            $result = DB::table('games')
                ->selectRaw('ST_Y(base_location) as lat, ST_X(base_location) as lng')
                ->where('id', $this->id)
                ->first();

            return $part === 'lat' ? (float) $result->lat : (float) $result->lng;
        } catch (\Exception $e) {
            \Log::error('Base location parsing error: ' . $e->getMessage());
            return null;
        }
    }

}
