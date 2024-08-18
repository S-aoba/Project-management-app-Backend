<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeRoleRequest;
use App\Http\Requests\InviteMemberRequest;
use App\Http\Requests\RemoveMemberRequest;
use App\Http\Requests\ShowProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\ProjectUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
            'data' => $projects
        ]);
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
                'message' => 'Project creation failed!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {        
        // プロジェクトに関連するユーザーとそのプロジェクト内のロールをロード
        if(Gate::allows('show', $project)){
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
        $project->update($request->validated());

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
        if(Gate::allows('delete', $project)){
            $res = $project->delete();
            
            if ($res) {
                return response()->json([
                    'status' => true,
                    'message' => 'Project deleted Successfully'
                ], 200);    
            }
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
        ], 200);
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

    public function changeOfRole(ChangeRoleRequest $request) 
    {
        try {
            $adminId = Auth::id();
            $projectId = $request->projectId();
            
            ProjectUser::where('project_id', $projectId)
                            ->where('user_id', $adminId)
                            ->update(['role_id' => 2]);
    
            // 対象のmemberをadminに変更
            $newAdminId = $request->userId();
            ProjectUser::where('project_id', $projectId)
                            ->where('user_id', $newAdminId)
                            ->update(['role_id' => 1]);
            
            return response()->json([
                'status' => true,
                'message' => 'Role updated Successfully!'
            ], 200);
            
        } catch (\Illuminate\Database\QueryException $e) {
            // データベースエラーが発生した場合
            return response()->json([
                'message' => 'システムエラーが発生しました。しばらく後に再度お試しください。'
            ], 500);
        }
    }
}
