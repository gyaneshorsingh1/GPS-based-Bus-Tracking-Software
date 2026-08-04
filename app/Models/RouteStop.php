<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouteStop extends Model
{
    protected $fillable = [
        'route_id',
        'name',
        'latitude',
        'longitude',
        'stop_order',
        'pickup_time',
        'drop_time',
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}
