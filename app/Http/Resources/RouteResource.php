<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RouteResource extends JsonResource
{
    /**
     * Transform the route into a safe API array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'route_code' => $this->route_code,
            'start_location' => $this->start_location,
            'end_location' => $this->end_location,
            'estimated_distance' => $this->estimated_distance,
            'estimated_duration' => $this->estimated_duration,
            'is_active' => (bool) $this->is_active,
            'stops' => $this->whenLoaded('stops', $this->stops->map(fn ($stop) => [
                'id' => $stop->id,
                'name' => $stop->name,
                'latitude' => $stop->latitude,
                'longitude' => $stop->longitude,
                'stop_order' => $stop->stop_order,
                'pickup_time' => $stop->pickup_time,
                'drop_time' => $stop->drop_time,
                'is_active' => (bool) $stop->is_active,
            ])),
        ];
    }
}
