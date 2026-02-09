<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectsRequest;
use Illuminate\Http\Request;
//Models
use App\Models\Project;
use App\Models\User;
use App\Models\EmployeeProject;

use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class ProjectsController extends Controller
{
    public function createProject(CreateProjectsRequest $request) {
        $data = $request->validated();
        $authUser = Auth::user();
        $data['created_by'] = $authUser->employee->id;
        // Create the project with the validated data
        $project = Project::create($data);
        // Load the creator relationship
        $project->load('creator');
        return response()->json([
            'message' => 'Project created successfully',
            'project_data' => [
                'id' => $project->id,
                'title' => $project->title,
                'description' => $project->description,
                'start_date' => $project->start_date ? $project->start_date->format('m.d.y') : null,
                'due_date' => $project->due_date ? $project->due_date->format('m.d.y') : null,
                'priority' => $project->priority,
                'status' => $project->status,
                'created_by' => $project->creator->employee_name,
            ]
        ], 201);
    }

    public function deleteProject($id) {
    }

    public function editProject(Request $request, $id) {
        // Logic to edit a project
    }

    public function listProjects(){

        //Variables
        $active_user = Auth::user();
        $employee_id = $active_user->employee->id;
        $user_role = $active_user->role;


        //Check role for permission
        if ($user_role === 'admin') {

            $projects = Project::all();

        } elseif ($user_role === 'manager'){

            $projects = Project::where('created_by', $employee_id)->get();

        } else {

            return response()->json([
                'message' => "You don't have permissions for this action. Please contact your CPO or HR"
            ]);

        }

        //Formatting project's list
        if ($projects->isEmpty()){

            $formatting_projects = "You don't have projects yet";

        } else {
        
            $formatting_projects = $projects->map(function ($p){
                return [
                    'id' => $p->id,
                    'project_title' => $p->title,
                    'project_description' => $p->description,
                    'start_date' => $p->start_date ? $p->start_date->format('m.d.y') : null,
                    'due_date' => $p->due_date ? $p->due_date->format('m.d.y') : null,
                    'priority' => $p->priority,
                    'status' => $p->status,
                    'created_by' => $p->creator->employee_name
                ];
            });

        }

        return response()->json([
            'message' => 'All projects list:',
            'data' => $formatting_projects
        ]);
    }
}
