<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeAssignedUserIdRequest;
use App\Http\Requests\DestroyTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function store(StoreTaskRequest $request)
    {
        try {
            $validatedData = $request->validated();
            Task::create([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'due_date' => $validatedData['dueDate'],
                'status' => $validatedData['status'],
                'image_path' => $validatedData['imagePath'],
                'priority' => $validatedData['priority'],
                'assigned_user_id' => $validatedData['assignedUserId'],
                'project_id' => $validatedData['projectId'],
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ]);
    
            return response()->json([
                'message' => 'Task created succesfully!'
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        try {
            $validatedData = $request->validated();
            $task->update([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'due_date' => $validatedData['dueDate'],
                'status' => $validatedData['status'],
                'image_path' => $validatedData['imagePath'],
                'priority' => $validatedData['priority'],
                'project_id' => $validatedData['projectId'],
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ]);
    
            return response()->json([
                'message' => 'Task updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function changeAssignedUserId(ChangeAssignedUserIdRequest  $request, Task $task)
    {
        try {
            $newAssignedUserId = $request->validated()['newAssignedUserId'];

            if ($task['assigned_user_id'] === $newAssignedUserId)
            {
                throw new Exception('The assigned user is already selected. Please specify a different user.', 403);
            }
            
            $newAssignedUser = User::find($newAssignedUserId);
    
            $project = Project::find($task['project_id']);
            
            if(!$newAssignedUser->can('checkJoinProject', $project))
            {
                throw new Exception('new assigned user is not in project.', 403);
            }
    
           $task->update([
            'assigned_user_id' => $newAssignedUserId
           ]);

           return response()->json([
            'message' => 'Updated assignedUserId successfully!'
           ],200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function destroy(Request $request, Task $task)
    {
        try {
            if($request->user()->id === $task['assigned_user_id'])
            {
                $res = $task->delete();
    
                if($res) {
                    return response()->json([
                        'message' => 'Task deleted successfully'
                    ], 200);
                }
            }
            throw new Exception('Unauthenticated.', 401);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
 