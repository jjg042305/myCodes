<?php

namespace App\AdminApi\Resources\ReferralPrograms;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReferralProgramResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => (string)$this->resource->id,
            'program_name' => (string)$this->resource->program_name,
            'program_title' => (string)$this->resource->program_title,
            'partner_id' => (int)$this->resource->partner_id,
            'created_at' => (string)$this->resource->created_at,
            'is_active' => (bool)$this->resource->is_active,
            'description' => (string)$this->resource->description
        ];
    }
}
