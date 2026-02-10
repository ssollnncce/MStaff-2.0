<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'project_title' => $this->title,
            'project_description' => $this->description,
            'start_date' => $this->start_date ? $this->start_date->format('m.d.y') : null,
            'due_date' => $this->due_date ? $this->due_date->format('m.d.y') : null,
            'priority' => $this->priority,
            'status' => $this->status,
            'created_by' => $this->creator?->employee_name,
            'participants' => $this->employees->map(function ($e) {
                return [
                    'id' => $e->id,
                    'name' => $e->employee_name,
                ];
            }),
        ];
    }
}
