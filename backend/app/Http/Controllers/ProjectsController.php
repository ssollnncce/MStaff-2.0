<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectsRequest;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

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
        // Logic to delete a project
    }

    public function editProject(Request $request, $id) {
        // Logic to edit a project
    }
}
