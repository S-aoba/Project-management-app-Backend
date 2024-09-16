<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // ユーザーがProjectに参加しているかどうかを判定
        $projectId = $this->input('project_id');
        $user = Auth::user();
        
        $isJoinedProject = $user->projects->where('id', $projectId)->isNotEmpty();

        return $isJoinedProject; 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:1|max:50',
            'description' => 'nullable|max:1000',
            'due_date' => 'nullable|date|after_or_equal:today',
            'status' => 'required|in:pending,is_progress,completed',
            'image_path' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'assigned_user_id' => 'required|int|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.min' => 'The name must be at least 1 character.',
            'name.max' => 'The name may not be greater than 50 characters.',
            
            'description.max' => 'The description may not be greater than 1000 characters.',
            
            'due_date.date' => 'The due date must be a valid date.',
            'due_date.after_or_equal' => 'The due date must be today or a future date.',
            
            'status.required' => 'The status field is required.',
            'status.in' => 'The status must be one of the following: pending, is_progress, completed.',
            
            'image_path.string' => 'The image path must be a string.',
            
            'priority.required' => 'The priority field is required.',
            'priority.in' => 'The priority must be one of the following: low, medium, high.',
            
            'assigned_user_id.required' => 'The assigned user ID field is required.',
            'assigned_user_id.int' => 'The assigned user ID must be an integer.',
            'assigned_user_id.exists' => 'The selected assigned user ID does not exist.',
        ];
    }

}
