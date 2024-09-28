<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskResource;
use App\Http\Resources\UserResource;
use App\Models\Project;
use Exception;
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
        try {
            if($request->user()->cannot('view', $project)){
                throw new Exception('You are not authorized to view this project.', 403);
            }
    
            $project = $project->load(['users.roles' => function($query) use ($project) {
                $query->where('project_id', $project->id)->select('name');
            }, 'tasks']);
    
            
            return response()->json([
                'project' => new ProjectResource($project),
                'tasks' => TaskResource::collection($project['tasks']),
                'users' => UserResource::collection($project['users'])
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to show project: ' . $e->getMessage());

            return response()->json([
                'message' => $e->getMessage(),
            ],$e->getCode());
        }
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
                    'message' => 'Project updated successfully'
                ], 200);
            }
        } catch (\Exception $e) {
            Log::error('Failed to update project: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while updating the project.',
            ], 500);
        }
    }

    public function destroy(Request $request, Project $project)
    {
        try {
            if($request->user()->cannot('delete', $project)){
                throw new Exception('You are not authorized to destroy this project', 403);
            }
            
            $res = $project->delete();
            if ($res) {
                return response()->json([
                    'message' => 'Project deleted successfully'
                ], 200);    
            }

        } catch (\Exception $e) {
            Log::error('Failed to delete project: ' . $e->getMessage());

            return response()->json([
                'message' => $e->getMessage(),
            ],$e->getCode());
        }
    }
}
