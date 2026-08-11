<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusResource extends JsonResource
{
    /**
     * Transform the bus into a safe API array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bus_number' => $this->bus_number,
            'registration_number' => $this->registration_number,
            'make' => $this->make,
            'model' => $this->model,
            'year' => $this->year,
            'capacity' => $this->capacity,
            'fuel_type' => $this->fuel_type,
            'status' => $this->status,
            'route_id' => $this->route_id,
            'driver' => $this->whenLoaded('driver', fn () => $this->driver ? [
                'id' => $this->driver->id,
                'name' => $this->driver->full_name,
                'phone' => $this->driver->phone,
                'profile_photo' => $this->driver->profile_photo,
            ] : null),
            'route' => $this->whenLoaded('route', fn () => $this->route ? new RouteResource($this->route) : null),
        ];
    }
}
