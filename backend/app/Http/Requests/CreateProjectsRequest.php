<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProjectsRequest extends FormRequest
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
            'start_date' => 'required|date',
            'due_date' => 'required|date|after:start_date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:planning, in_progress,completed,on_hold,declined',
            'employee_ids' => 'nullable|array',
            'employee_ids.*' => 'integer|exists:employees,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Project title is required',
            'title.string' => 'Project title must be a string',
            'title.max' => 'Project title cannot exceed 255 characters',
            'description.string' => 'Project description must be a string',
            'start_date.required' => 'Start date is required',
            'start_date.date' => 'Start date must be a valid date',
            'due_date.required' => 'Due date is required',
            'due_date.date' => 'Due date must be a valid date',
            'due_date.after' => 'Due date must be after start date',
            'priority.required' => 'Priority is required',
            'priority.in' => 'Priority must be one of: low, medium, high',
            'status.required' => 'Status is required',
            'status.in' => 'Status must be one of: planning, in_progress, completed, on_hold, declined',
            'employee_ids.array' => 'Employee ids must be an array',
            'employee_ids.*.integer' => 'Employee id must be an integer',
            'employee_ids.*.exists' => 'Employee id must exist in employees table',
        ];
    }
}
