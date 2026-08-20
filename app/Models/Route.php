<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $fillable = [
        'asal',
        'tujuan',
        'jarak',
        'estimasi',
        'gambar',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
