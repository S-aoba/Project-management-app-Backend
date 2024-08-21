<?php

namespace App\Http\Controllers;

use App\Http\Requests\InviteMemberRequest;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\User;
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
     * Update the user role.
     */
    public function update(Project $project, User $user)
    {
        try {
            if(Gate::allows('changeRole', $project)) {
                ProjectUser::changeUserRole($project->id, $user->id);
                
                return response()->json([
                    'message' => 'Role updated Successfully!'
                ], 200);    
            }
            
            abort(403, 'This action is unauthorized.');
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);

        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'システムエラーが発生しました。しばらく後に再度お試しください。'
            ], 500);
        }
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

            abort(403, 'This action is unauthorized.');
        
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