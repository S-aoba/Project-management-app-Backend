<?php

namespace App\Http\Controllers;

use App\Http\Requests\InviteMemberRequest;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectUserController extends Controller
{
    /**
     * Invite newly user as member in the project.
     */
    public function store(InviteMemberRequest $request, Project $project)
    {
        try {
            
            ProjectUser::addUserToProject($project->id, $request->validated()['user_id'], 2);
    
            return response()->json([
                'message' => 'User invited Successfully!'
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);

        } catch (\Illuminate\Database\QueryException $e) {
            
            return response()->json([
                'message' => 'A system error has occurred. Please try again in a few moments.',
                'error_code' => 500
            ], 500);
        }
    }

    /**
     * Update user role.
     */
    public function update(Request $request, Project $project)
    {
        // 一旦コメントアウト
        // try {
        //     $adminId = Auth::id();
        //     $projectId = $request->projectId();
            
        //     ProjectUser::where('project_id', $projectId)
        //                     ->where('user_id', $adminId)
        //                     ->update(['role_id' => 2]);
    
        //     $newAdminId = $request->userId();
        //     ProjectUser::where('project_id', $projectId)
        //                     ->where('user_id', $newAdminId)
        //                     ->update(['role_id' => 1]);
            
        //     return response()->json([
        //         'status' => true,
        //         'message' => 'Role updated Successfully!'
        //     ], 200);
            
        // } catch (\Illuminate\Database\QueryException $e) {

        //     return response()->json([
        //         'message' => 'システムエラーが発生しました。しばらく後に再度お試しください。'
        //     ], 500);
        // }
    }

    /**
     * Remove the user to the project.
     */
    public function destroy(Project $project, User $user)
    {
        try {
            if(Gate::allows('removeMember', $project)) {
                ProjectUser::removeUserToProject($project->id, $user->id);

                return response()->json([
                    'message' => 'Member removed Successfully!',
                ], 200);
            }

            abort(400, 'This action is unauthorized.');
        
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);

        } catch (\Illuminate\Database\QueryException $e) {
            
            return response()->json([
                'message' => 'A system error has occurred. Please try again in a few moments.'
            ], 500);
        }
    }
}