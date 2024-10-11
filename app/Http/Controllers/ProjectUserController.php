<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectUserController extends Controller
{
    /**
     * Update the user role.
     */
    public function update(Request $request, Project $project, User $user)
    {
        try {
            if (!$request->user()->isAdmin($project)) {
                throw new AuthorizationException('Unauthorized.', 403);
            }

            if ($request->user()->id === $user->id) {
                throw new Exception('The admin role cannot be changed.', 403);
            }

            if ($user->cannot('checkJoinProject', $project)) {
                throw new Exception('Target user is not in the project.', 403);
            }

            DB::beginTransaction();

            ProjectUser::where('project_id', $project->id)
                        ->where('user_id', $request->user()->id)
                        ->update(['role_id' => 2]);

            ProjectUser::where('project_id', $project->id)
                        ->where('user_id', $user->id)
                        ->update(['role_id' => 1]);

            DB::commit();

            return response()->json([
                'message' => 'Change role successfully.'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Change user role failed: ' . $e->getMessage());

            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    /**
     * Remove the user to the project.
     */
    public function destroy(Request $request, Project $project, User $user)
    {
        try {
            if (!$request->user()->isAdmin($project)) {
                throw new Exception('Unauthorized.', 401);
            }

            if ($request->user()->id === $user->id) {
                throw new Exception('Admin user can not removed.', 400);
            }

            if ($user->cannot('checkJoinProject', $project)) {
                throw new Exception('The user to be deleted has not joined the project.', 403);
            }

            $res = ProjectUser::where('project_id', $project->id)
                                ->where('user_id', $user->id)
                                ->delete();

            if ($res) {
                return response()->json([
                    'message' => 'Member has been successfully removed from the project.'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
