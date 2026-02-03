<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentsController extends Controller
{
    public function allDepartments() {
        // Logic to retrieve and return all departments
        $departments = Department::all();

        return response()->json([
            'message' => 'Departments retrieved successfully',
            'count' => $departments->count(),
            'departments' => $departments
        ], 200);
    }

    public function createDepartment(Request $request) {
        // Logic to create a new department
        $data = $request->validate([
            'title' => 'required|string|unique:departments,title',
            'description' => 'nullable|string',
        ]);

        $department = Department::create($data);

        return response()->json([
            'message' => 'Department created successfully',
            'department' => $department
        ], 201);
    }

    public function deleteDepartment(int $id) {
        // Logic to delete a department by ID
        $department = Department::findOrFail($id);
        $department->delete();

        return response()->json([
            'message' => 'Department ' . $department->title . ' deleted successfully'
        ], 200);
    }

    public function editDepartment(Request $request, int $id) {
        // Logic to edit a department by ID
        $department = Department::findOrFail($id);
        $data = $request->validate([
            'title' => 'string|unique:departments,title,' . $department->id,
            'description' => 'nullable|string',
        ]);
        $department->update($data);

        return response()->json([
            'message' => 'Department ' . $department->title . ' updated successfully',
            'department' => $department
        ], 200);
    }
}
