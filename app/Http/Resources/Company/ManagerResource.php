<?php

namespace App\Http\Resources\Company;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ManagerResource extends JsonResource
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
            'sourceId' => $this->source_id,
            'fullName' => $this->full_name,
            'city' => $this->city?->name,
            'company' => $this->company?->name,
            'division' => $this->division?->name,
            'subdivision' => $this->subdivision?->name,
            'directions' => $this->directions?->pluck('name')->toArray(),
            'position' => $this->position?->name,
            'level' => $this->level?->name,
        ];
    }
}
