<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEmployeeRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:users,id|unique:employees,user_id',
            'department_id' => 'required|integer|exists:departments,id',
            'position_id' => 'required|integer|exists:positions,id',
            'employment_date' => 'required|date',
            'fired_date' => 'nullable|date|after_or_equal:employment_date',
            'status_id' => 'nullable|integer|exists:employee_statuses,id',
            'work_format' => 'required|in:office,remote,hybrid',
            'time_zone' => 'required|string|timezone',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'User ID is required',
            'user_id.exists' => 'The selected user does not exist',
            'user_id.unique' => 'This user is already an employee',
            'department_id.required' => 'Department is required',
            'department_id.exists' => 'The selected department does not exist',
            'position_id.required' => 'Position is required',
            'position_id.exists' => 'The selected position does not exist',
            'employment_date.required' => 'Employment date is required',
            'employment_date.date' => 'Employment date must be a valid date',
            'fired_date.date' => 'Fired date must be a valid date',
            'fired_date.after_or_equal' => 'Fired date must be after or equal to employment date',
            'work_format.required' => 'Work format is required',
            'work_format.in' => 'Work format must be one of: office, remote, hybrid',
            'time_zone.required' => 'Time zone is required',
            'time_zone.timezone' => 'The selected time zone is invalid',
        ];
    }
}
