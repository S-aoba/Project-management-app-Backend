<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();

        return ProjectResource::collection($projects);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        try {
            $validatedData = $request->validated();
            
            $project = Project::createProjectAndAssignAdmin($validatedData);

            return response()->json([
                'status' => true,
                'message' => 'Project created Successfully!',
                'data' => $project
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'error_code' => 500,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {        
        // プロジェクトに関連するユーザーとそのプロジェクト内のロールをロード
        if(Gate::allows('view', $project)){
            $project = $project->load(['users.roles' => function($query) use ($project) {
                $query->where('project_id', $project->id)->select('name');
            }, 'tasks']);
    
            return response()->json([
                'status' => true,
                'data' => $project
            ], 200);
        }

        abort(403, 'You are not authorized to view this project.');
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
                    'status' => true,
                    'message' => 'Project updated Successfully!'
                ], 200);
            }
        } catch (\Exception $e) {
            Log::error('Failed to delete project: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating the project.',
                'error_code' => 500
            ], 500);
        }
    }
}
