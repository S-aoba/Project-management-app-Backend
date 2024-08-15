<?php

namespace App\Http\Controllers;

use App\Http\Requests\InviteMemberRequest;
use App\Http\Requests\RemoveMemberRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\updateProjectRequest;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\User;
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
        $project = $project->load(['users.roles' => function($query) use ($project) {
            $query->where('project_id', $project->id)->select('name');
        }, 'tasks']);

        return response()->json([
            'status' => true,
            'data' => $project
        ], 200);
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


    public function inviteAsMember(InviteMemberRequest $request, int $projectId)
    {
        
        $userId = $request->user_id;
        
        ProjectUser::create([
            'project_id' => $projectId,
            'user_id' => $userId,
            'role_id' => 2,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User invited Successfully!'
        ]);
    }

    public function removeMember(RemoveMemberRequest $request)
    {
        $projectId = $request->projectId();
        $userId = $request->userId();
        
        $res = ProjectUser::where('project_id', $projectId)
                        ->where('user_id', $userId)
                        ->delete();

        if($res) {
            return response()->json([
                'status' => true,
                'message' => 'Member deleted Successfully!'
            ], 200);
        }
    }
}
