<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'school_id',
        'parent_id',
        'bus_id',
        'route_id',
        'admission_no',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'grade',
        'section',
        'roll_no',
        'pickup_location',
        'drop_location',
        'pickup_latitude',
        'pickup_longitude',
        'drop_latitude',
        'drop_longitude',
        'photo',
        'is_active',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function parent()
    {
        return $this->belongsTo(ParentProfile::class);
    }

    // public function bus()
    // {
    //     return $this->belongsTo(Bus::class);
    // }

    // public function route()
    // {
    //     return $this->belongsTo(Route::class);
    // }
}
