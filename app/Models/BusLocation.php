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

    public function gpsDevice()
    {
        return $this->belongsTo(GpsDevice::class);
    }
}
