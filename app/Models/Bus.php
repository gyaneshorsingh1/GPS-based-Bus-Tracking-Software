<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    protected $fillable = [
        'bus_number',
        'bus_name',
        'registration_number',
        'capacity',
        'status'
    ];
}