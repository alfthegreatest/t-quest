<?php

namespace App\Models;

use App\Models\Level;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $table = 'level_codes';

    protected $fillable = [
        'id',
        'level_id',
        'code',
    ];

    protected $hidden = [];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function levelId()
    {
        return $this->hasMany(Level::class);
    }

}
