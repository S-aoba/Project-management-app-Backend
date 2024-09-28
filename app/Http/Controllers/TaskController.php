<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskController extends Controller
{
    public function store(StoreTaskRequest $request)
    {
        try {
            Task::storeTask($request);
    
            return response()->json([
                'status' => true,
                'message' => 'Task created Succesfully!'
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Task updated Successfully!'
        ], 200);
    }

    public function destroy(Task $task)
    {
        $res = $task->delete();

        if($res) {
            return response()->json([
                'status' => true,
                'message' => 'Task deleted Successfully'
            ], 200);
        }
    }
}
