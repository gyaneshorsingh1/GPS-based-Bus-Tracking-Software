<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $fillable = [
        'school_id',
        'bus_id',
        'driver_id',
        'name',
        'route_code',
        'start_location',
        'end_location',
        'estimated_distance',
        'estimated_duration',
        'is_active',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // public function bus()
    // {
    //     return $this->belongsTo(Bus::class);
    // }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function stops()
    {
        return $this->hasMany(RouteStop::class)
            ->orderBy('stop_order');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
