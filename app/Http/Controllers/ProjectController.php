<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Type\Integer;

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
    public function store(Request $request)
    {
        $validatedData = $this->validateProjectInfo($request);

        $project = Project::createProject($validatedData);
        

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
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }

    private function validateProjectInfo(Request $req)
    {
        return $req->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'status' => 'required|string',
            'image_path' => 'nullable|string'
        ]);
    }
}
