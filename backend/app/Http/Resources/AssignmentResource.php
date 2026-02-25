<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'project_id' => $this->project?->title,
            'priority' => $this->priority,
            'status' => $this->status,
            'start_date' => $this->start_date ? $this->start_date->format('m.d.y') : null,
            'due_date' => $this->due_date ? $this->due_date->format('m.d.y') : null,
            'created_by' => $this->creator?->employee_name,
        ];
    }
}
