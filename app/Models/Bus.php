<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    protected $fillable = [
        'school_id',
        'driver_id',
        'bus_number',
        'registration_number',
        'capacity',
        'status',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function routes()
    {
        return $this->hasMany(Route::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
