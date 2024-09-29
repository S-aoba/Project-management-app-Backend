<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
                'message' => 'Task created Succesfully!'
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
        $validatedData = $request->validated();
        $task->update([
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
            'message' => 'Task updated Successfully!'
        ], 200);
    }

    public function destroy(Task $task)
    {
        $res = $task->delete();

        if($res) {
            return response()->json([
                'message' => 'Task deleted Successfully'
            ], 200);
        }
    }
}
