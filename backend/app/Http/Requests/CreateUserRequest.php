<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'patronymic' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|max:17|unique:users',
            'date_of_birth' => 'required|date',
            'password' => 'required|string|min:8|max:64|confirmed|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*?&]/',
            'role' => 'required|string|in:line_worker,manager,admin'
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'first_name.string' => 'First name must be a string.',
            'last_name.required' => 'Last name is required.',
            'last_name.string' => 'Last name must be a string.',
            'patronymic.required' => 'Patronymic is required.',
            'patronymic.string' => 'Patronymic must be a string.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
            'phone_number.required' => 'Phone number is required.',
            'phone_number.max' => 'Phone number must not exceed 17 characters.',
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.date' => 'Please provide a valid date of birth.',
            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a string.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password must not exceed 64 characters.',
            'password.confirmed' => 'Passwords do not match.',
            'password.regex' => 'Password must contain at least one lowercase letter, one uppercase letter, one digit, and one special character.',
            'role.required' => 'Role is required.',
            'role.string' => 'Role must be a string.',
            'role.in' => 'Role must be one of: line_worker, manager, admin.',
        ];
    }
}
