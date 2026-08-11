<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class StudentResource extends JsonResource
{
    /**
     * Transform the student into a safe API array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'admission_no' => $this->admission_no,
            'full_name' => $this->full_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'gender' => $this->gender,
            'grade' => $this->grade,
            'section' => $this->section,
            'roll_no' => $this->roll_no,
            'date_of_birth' => $this->date_of_birth?->toDateString(),
            'photo' => $this->photo ? Storage::url($this->photo) : null,
            'pickup_location' => $this->pickup_location,
            'drop_location' => $this->drop_location,
            'pickup_latitude' => $this->pickup_latitude,
            'pickup_longitude' => $this->pickup_longitude,
            'drop_latitude' => $this->drop_latitude,
            'drop_longitude' => $this->drop_longitude,
            'is_active' => (bool) $this->is_active,
            'bus_id' => $this->bus_id,
            'bus' => $this->whenLoaded('bus', fn () => $this->bus ? new BusResource($this->bus) : null),
        ];
    }
}
