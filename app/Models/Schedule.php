<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'bus_id',
        'route_id',
        'tanggal',
        'jam_berangkat',
        'jam_tiba',
        'harga',
        'kursi_tersedia',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
