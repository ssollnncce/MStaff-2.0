<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignmentCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|integer|exists:projects,id',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:planning,in_process,completed,on_hold',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'creator_id' => 'nullable|integer|exists:employees,id',
        ];
    }

     public function messages(): array
    {
        return [
            'title.required' => 'Assignment title is required',
            'title.string' => 'Assignment title must be a string',
            'title.max' => 'Assignment title cannot exceed 255 characters',
            'description.string' => 'Assignment description must be a string',
            'project_id.required' => 'Project is required',
            'project_id.integer' => 'Project id must be an integer',
            'project_id.exists' => 'Project must exist',
            'priority.required' => 'Priority is required',
            'priority.in' => 'Priority must be one of: low, medium, high',
            'status.required' => 'Status is required',
            'status.in' => 'Status must be one of: planning, in_process, completed, on_hold',
            'start_date.date' => 'Start date must be a valid date',
            'due_date.date' => 'Due date must be a valid date',
            'due_date.after_or_equal' => 'Due date must be after or equal to start date',
            'creator_id.integer' => 'Creator id must be an integer',
            'creator_id.exists' => 'Creator must exist in employees table',
        ];
    }
}
