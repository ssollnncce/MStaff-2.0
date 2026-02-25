<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

//models
use App\Models\User;
use App\Models\Assignment;
use App\Models\Project;

//requests
use App\Http\Requests\AssignmentCreateRequest;

//resources
use App\Http\Resources\AssignmentResource;


class AssignmentsController extends Controller
{
    public function assignmentCreate(AssignmentCreateRequest $request) {
        $active_user = Auth::user();
        $employee_id = $active_user->employee->id;

        /*Create credentials for creating assgnment
            Credentials fields: title, description, project_id, priority, status, start_date due_date
            Credentials values: from request, creator_id from active user employee id
        */

        $credentials = $request->validated();
        $credentials['creator_id'] = $employee_id;

        $memberProjectIds = $active_user->employee
            ->projects()
            ->pluck('projects.id');

        $createdProjectIds = Project::where('created_by', $employee_id)
            ->pluck('id');

        $available_projects = $memberProjectIds
            ->merge($createdProjectIds)
            ->unique()
            ->values()
            ->toArray();

        if (!in_array($credentials['project_id'], $available_projects)) {
            return response()->json([
                'message' => 'You can only create assignments for projects you are part of or have created. Please contact with your HR or your CPO'
            ], 403);
        }
        $available_employees = $active_user->employee->projects()
            ->where('id', $credentials['project_id'])
            ->first()
            ->employees()
            ->pluck('id')
            ->toArray();

        if (isset($credentials['assignee_id']) && !in_array($credentials['assignee_id'], $available_employees)) {
            return response()->json([
                'message' => 'You can only assign tasks to employees who are part of the project. Please contact with your HR or your CPO'
            ], 403);
        }

        if ($active_user->role === 'admin') {
            $assignment = Assignment::create($credentials);

            return response()->json([
                'message' => 'Assignment created successfully',
                'assignment' => new AssignmentResource($assignment)
            ], 201);
        }
        // $assignment = Assignment::create($credentials);

        // return response()->json([
        //     'message' => 'Assignment created successfully',
        //     'assignment' => $assignment
        // ], 201);

    }
}
