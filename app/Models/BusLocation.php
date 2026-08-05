<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusLocation extends Model
{
    protected $fillable = [
        'gps_device_id',
        'latitude',
        'longitude',
        'speed',
        'heading',
        'altitude',
        'recorded_at',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'speed' => 'float',
        'heading' => 'float',
        'altitude' => 'integer',
        'recorded_at' => 'datetime',
    ];

    public function gpsDevice()
    {
        return $this->belongsTo(GpsDevice::class);
    }
}
