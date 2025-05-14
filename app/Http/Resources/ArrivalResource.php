<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArrivalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'date' => $this->date,
            'arrival_time' => $this->arrival_time,
            'departure_time' => $this->departure_time,
            'late' => $this->late,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
