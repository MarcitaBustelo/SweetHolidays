<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'NIF' => $this->NIF,
            'role' => $this->role,
            'phone' => $this->phone,
            'employee_id' => $this->employee_id,
            'department_id' => $this->department_id,
            'delegation_id' => $this->delegation_id,
            'responsable' => $this->responsable,
            'days' => $this->days,
            'days_in_total' => $this->days_in_total,
            'active' => $this->active,
            'start_date' => $this->start_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
