<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
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
            'department_id' => 'sometimes|integer|exists:departments,id',
            'position_id' => 'sometimes|integer|exists:positions,id',
            'employment_date' => 'sometimes|date',
            'fired_date' => 'sometimes|nullable|date|after_or_equal:employment_date',
            'status_id' => 'sometimes|nullable|integer|exists:employee_statuses,id',
            'work_format' => 'sometimes|in:office,remote,hybrid',
            'time_zone' => 'sometimes|string|timezone',
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
            'department_id.exists' => 'The selected department does not exist',
            'position_id.exists' => 'The selected position does not exist',
            'employment_date.date' => 'Employment date must be a valid date',
            'fired_date.date' => 'Fired date must be a valid date',
            'fired_date.after_or_equal' => 'Fired date must be after or equal to employment date',
            'work_format.in' => 'Work format must be one of: office, remote, hybrid',
            'time_zone.timezone' => 'The selected time zone is invalid',
        ];
    }
}
