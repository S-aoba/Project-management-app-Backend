<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskResource;
use App\Http\Resources\UserResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        return ProjectResource::collection($projects);
    }

    public function store(StoreProjectRequest $request)
    {
        try {
            $validatedData = $request->validated();
            
            $project = Project::createProjectAndAssignAdmin($validatedData);

            return response()->json([
                'data' => $project,
                'message' => 'Project created successfully.'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function show(Request $request, Project $project)
    {        
        if($request->user()->cannot('view', $project)){
            return response()->json([
                'message' => 'You are not authorized to view this project.'
            ], 403);
        }

        $project = $project->load(['users.roles' => function($query) use ($project) {
            $query->where('project_id', $project->id)->select('name');
        }, 'tasks']);

        
        return response()->json([
            'project' => new ProjectResource($project),
            'tasks' => TaskResource::collection($project['tasks']),
            'users' => UserResource::collection($project['users'])
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        try {
            $res = $project->update($request->validated());
    
            if($res) {
                return response()->json([
                    'message' => 'Project updated Successfully!'
                ], 200);
            }
        } catch (\Exception $e) {
            Log::error('Failed to delete project: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while updating the project.',
            ], 500);
        }
    }

    public function destroy(Project $project)
    {
        try {
            if(Gate::allows('delete', $project)){
                $res = $project->delete();

                if ($res) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Project deleted Successfully'
                    ], 200);    
                }
            }

            abort(403, 'You are not authorized to destroy this project.');
        } catch (\Exception $e) {
            Log::error('Failed to delete project: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while deleting the project.',
                'errorCode' => 500
            ], 500);
        }
    }
}
