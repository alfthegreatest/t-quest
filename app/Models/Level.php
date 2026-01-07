<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Level extends Model
{
    protected $fillable = [
        'order',
        'game_id',
        'name',
        'description',
        'coordinates',
        'availability_time',
    ];

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

    public function getCoordinatesAttribute($value)
    {
        if ($value) {
            $point = DB::selectOne("SELECT ST_X(?) as lng, ST_Y(?) as lat", [$value, $value]);
            return [
                'lat' => $point->lat,
                'lng' => $point->lng,
            ];
        }
        return null;
    }
}