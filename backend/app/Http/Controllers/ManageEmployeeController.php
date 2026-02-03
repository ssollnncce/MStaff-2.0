<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use Illuminate\Http\Request;

class ManageEmployeeController extends Controller
{
    /**
     * Create a new employee from an existing user.
     */
    public function createEmployee(CreateEmployeeRequest $request) {
        $data = $request->validated();

        // Verify that the user exists
        $user = User::find($data['user_id']);
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        // Create new employee
        $employee = Employee::create($data);

        return response()->json([
            'message' => 'Employee created successfully',
            'employee_data' => [
                'employee_name' => $employee->user ? $employee->user->full_name() : null,
                'employee_department' => $employee->department ? $employee->department->title : null,
                'employee_position' => $employee->position ? $employee->position->title : null,
                'employment_date' => $employee->employment_date ? $employee->employment_date->format('m.d.y') : null,
                'fired_date' => $employee->fired_date ? $employee->fired_date->format('m.d.y') : null,
                'employee_status' => $employee->status ? $employee->status->status_name : null,
                'work_format' => $employee->work_format,
                'time_zone' => $employee->time_zone,
            ],
        ], 201);
    }

    /**
     * Update an existing employee.
     */
    public function editEmployee(UpdateEmployeeRequest $request, int $id) {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'message' => 'Employee not found',
            ], 404);
        }

        $data = $request->validated();
        $employee->update($data);

        return response()->json([
            'message' => 'Employee data updated successfully',
            'employee_data' => [
                'employee_id' => $employee->id,
                'employee_user_id' => $employee->user_id,
                'employee_name' => $employee->user ? $employee->user->full_name() : null,
                'employee_department' => $employee->department ? $employee->department->title : null,
                'employee_position' => $employee->position ? $employee->position->title : null,
                'employment_date' => $employee->employment_date ? $employee->employment_date->format('m.d.y') : null,
                'fired_date' => $employee->fired_date ? $employee->fired_date->format('m.d.y') : null,
                'employee_status' => $employee->status ? $employee->status->status_name : null,
                'work_format' => $employee->work_format,
                'time_zone' => $employee->time_zone,
            ]
        ], 200);
    }

    /**
     * Delete an employee.
     */
    public function deleteEmployee(int $id) {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'message' => 'Employee not found',
            ], 404);
        }

        $employee->delete();

        return response()->json([
            'message' => 'Employee deleted successfully',
        ], 200);
    }

    /**
     * Get all employees.
     */
    public function allEmployee() {
        // Return list of employee objects with appended/formatted fields.
        $employees = Employee::with('user', 'department', 'position', 'status')->get();

        $list = $employees->map(function ($e) {
            return [
                'id' => $e->id,
                'user_id' => $e->user_id,
                'employee_name' => $e->employee_name,
                'employee_department' => $e->employee_department,
                'employee_position' => $e->employee_position,
                'employment_date' => $e->employment_date_formatted,
                'fired_date' => $e->fired_date_formatted,
                'employee_status' => $e->employee_status,
                'work_format' => $e->work_format,
                'time_zone' => $e->time_zone,
            ];
        })->values();

        return response()->json([
            'message' => 'Employees list:',
            'count' => $list->count(),
            'employees' => $list,
        ], 200);
    }
}
