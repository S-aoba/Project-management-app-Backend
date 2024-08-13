<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\updateProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();

        return response()->json([
            'status' => true,
            'projects' => $projects
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $project = Project::createProject($request);
        
        return response()->json([
            'status' => true,
            'message' => 'Project created Successfully!',
            'project' => $project
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Project $project)
    {
        // プロジェクトに関連するユーザーとそのプロジェクト内のロールをロード
        return $project->load(['users.roles' => function($query) use ($project) {
            $query->where('project_id', $project->id)->select('name');
        }]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(updateProjectRequest $request, Project $project)
    {
        $project->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Project updated Successfully!'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $res = $project->delete();

        if ($res) {
            return response()->json([
                'status' => true,
                'message' => 'Project deleted Successfully'
            ], 200);    
        }
        
    }
}
