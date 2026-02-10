<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectsRequest;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\Request;
//Models
use App\Models\Project;
use App\Models\User;
use App\Models\EmployeeProject;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class ProjectsController extends Controller
{
    public function createProject(CreateProjectsRequest $request) {
        //Crdentials: 
            /* Data for create projects (Variable $data):
                 title,
                 description,
                 start_date,
                 due_date,
                 priority,
                 status,
            */
        $data = $request->validated();
        
        // Active user (Variable $authUser)
        $authUser = Auth::user();

        //Fill for detecting the project's creator
        $data['created_by'] = $authUser->employee->id;

        DB::transaction(function () use (&$project, $data, $request) {
            // Create the project with the validated data
            $project = Project::create($data);

            // Attach participants if provided (write to employee_projects explicitly)
            $employeeIds = $request->input('employee_ids', []);
            if (is_array($employeeIds) && !empty($employeeIds)) {
                $employeeIds = array_values(array_unique($employeeIds));
                $rows = array_map(function ($employeeId) use ($project) {
                    return [
                        'employee_id' => $employeeId,
                        'project_id' => $project->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }, $employeeIds);

                EmployeeProject::insert($rows);
            }
        });

        // Load relationships for response (from employee_projects)
        $project->load('creator.user', 'employees.user');

        //Response
        return response()->json([
            'message' => 'Project created successfully',
            'project_data' => new ProjectResource($project),
        ], 201);
    }

    public function deleteProject($id) {
        $active_user = Auth::user();
        $employee_id = $active_user->employee->id;
        $user_role = $active_user->role;

        $project = Project::find($id);
        if (!$project) {
            return response()->json([
                'message' => 'Project not found'
            ], 404);
        }

        // Admin can delete any project; manager can delete only own
        if ($user_role === 'manager' && $project->created_by !== $employee_id) {
            return response()->json([
                'message' => "You don't have permissions for this action. Please contact your CPO or HR"
            ], 403);
        }

        $project->delete();

        return response()->json([
            'message' => 'Project deleted successfully'
        ]);
    }

    public function editProject(Request $request, $id) {
        $active_user = Auth::user();
        $employee_id = $active_user->employee->id;
        $user_role = $active_user->role;

        $project = Project::with('creator.user', 'employees.user')->find($id);
        if (!$project) {
            return response()->json([
                'message' => 'This project not found'
            ], 404);
        }

        // Permissions: admin can edit any, manager only own, line_worker only if participant
        if ($user_role === 'manager' && $project->created_by !== $employee_id) {
            return response()->json([
                'message' => "You don't have permissions for this action. Please contact your CPO or HR"
            ], 403);
        }
        if ($user_role === 'line_worker') {
            $isParticipant = $project->employees()
                ->where('employees.id', $employee_id)
                ->exists();
            if (!$isParticipant) {
                return response()->json([
                    'message' => "You don't have permissions for this action. Please contact your CPO or HR"
                ], 403);
            }
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'start_date' => 'sometimes|date',
            'due_date' => 'sometimes|date|after:start_date',
            'priority' => 'sometimes|in:low,medium,high',
            'status' => 'sometimes|in:planning,in_progress,completed,on_hold,declined',
            'employee_ids' => 'sometimes|array',
            'employee_ids.*' => 'integer|exists:employees,id',
        ]);

        DB::transaction(function () use ($project, $validated, $request) {
            $project->fill($validated);
            $project->save();

            if ($request->has('employee_ids')) {
                $employeeIds = $request->input('employee_ids', []);
                if (!is_array($employeeIds)) {
                    $employeeIds = [];
                }
                $employeeIds = array_values(array_unique($employeeIds));
                $project->employees()->sync($employeeIds);
            }
        });

        $project->load('creator.user', 'employees.user');

        return response()->json([
            'message' => 'Project updated successfully',
            'project_data' => new ProjectResource($project),
        ]);
    }

    public function listProjects(){

        //Variables
        $active_user = Auth::user();
        $employee_id = $active_user->employee->id;
        $user_role = $active_user->role;


        //Check role for permission
        if ($user_role === 'admin') {
            $projects = Project::with('creator.user', 'employees.user')->get();
        } elseif ($user_role === 'manager'){
            $projects = Project::with('creator.user', 'employees.user')
                ->where('created_by', $employee_id)
                ->get();
        } else {
            $projects = Project::with('creator.user', 'employees.user')
                ->whereHas('employees', function($q) use ($employee_id){
                    $q->where('employees.id', $employee_id);
                })
                ->get();
        }

        //Formatting project's list
        if ($projects->isEmpty()){
            $formatting_projects = "You don't have projects yet";
        } else {
            $formatting_projects = ProjectResource::collection($projects);
        }

        return response()->json([
            'message' => 'All projects list:',
            'data' => $formatting_projects
        ]);
    }

    public function getDetails ($id) {
        $active_user = Auth::user();
        $employee_id = $active_user->employee->id;
        $user_role = $active_user->role;

        $project = Project::with('creator.user', 'employees.user')->find($id);
        if (!$project){
            return response()->json([
                'message' => 'This project not found'
            ]);
        }

        $hasAccess = $project->employees()->where('employees.id', $employee_id)->exists();

        if ($user_role === 'admin') {
            return response()->json([
                'message' => 'Data of' . ' ' . $project->title . ' ' . 'get succesfull',
                'data' => new ProjectResource($project),
            ]);
        } elseif ($user_role === 'manager' && $project->created_by === $employee_id) {
            return response()->json([
                'message' => 'Data of' . ' ' . $project->title . ' ' . 'get succesfull',
                'data' => new ProjectResource($project),
            ]);
        } elseif ($user_role === 'line_worker' && $hasAccess){
            return response()->json([
                'message' => 'Data of' . ' ' . $project->title . ' ' . 'get succesfull',
                'data' => new ProjectResource($project),
            ]);
        } else {
            return response()->json([
                'message' => "You don't have acces to this project. Please contact with your CPO or HR"
            ], 401);
        }


    }
}
