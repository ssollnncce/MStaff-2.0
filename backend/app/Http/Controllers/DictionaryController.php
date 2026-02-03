<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\EmployeeStatus;

class DictionaryController extends Controller
{
    //Futures for management of employee statuses
    public function createEmployeeStatus(Request $request) {
        // Implementation for creating an employee status
        $status = $request->validate([
            'status_name' => 'required|string|unique:employee_statuses,status_name',
        ]);
        $employeeStatus = EmployeeStatus::create($status);
        return response()->json([
            'message' => 'Employee status created successfully',
            'employee_status' => $employeeStatus
        ], 201);
    }
    public function deleteEmployeeStatus(int $id) {
        // Implementation for deleting an employee status
        $employeeStatus = EmployeeStatus::findOrFail($id);
        $employeeStatus->delete();
        return response()->json([
            'message' => 'Employee status ' . $employeeStatus->status_name . ' deleted successfully'
        ], 200);
    }
    public function editEmployeeStatus(Request $request, int $id) {
        // Implementation for editing an employee status
        $employeeStatus = EmployeeStatus::findOrFail($id);
        $data = $request->validate([
            'status_name' => 'required|string|unique:employee_statuses,status_name,' . $employeeStatus->id,
        ]);
        $employeeStatus->update($data);
        return response()->json([            
            'message' => 'Employee status ' . $employeeStatus->status_name . ' updated successfully',
            'employee_status' => $employeeStatus
        ], 200);
    }
    public function listEmployeeStatuses() {
        // Implementation for listing all employee statuses
        $statuses = EmployeeStatus::all();
        return response()->json([
            'message' => 'Employee statuses retrieved successfully',
            'count' => $statuses->count(),
            'employee_statuses' => $statuses
        ], 200);
    }
}
