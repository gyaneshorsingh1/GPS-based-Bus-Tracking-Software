<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GpsDevice extends Model
{
    protected $fillable = [
        'school_id',
        'bus_id',
        'device_name',
        'device_imei',
        'sim_number',
        'status',
        'installed_at',
        'notes',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function locations()
    {
        return $this->hasMany(BusLocation::class);
    }
}
