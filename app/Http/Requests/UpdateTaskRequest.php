<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        $assignedUserId = $this->route('task')['assigned_user_id'];
        if ($assignedUserId !== $this->user()->id) {
            return false;
        }

        $projectId = $this->input('projectId');
        $project = Project::find($projectId);

        if (!$project) {
            return false;
        }

        if (!$this->user()->can('checkJoinProject', $project)) {
            return false;
        }
        return true;
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
            'dueDate' => 'nullable|date|after_or_equal:today',
            'status' => 'required|in:pending,progress,completed',
            'imagePath' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'projectId' => 'required|int|exists:projects,id'
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

            'dueDate.date' => 'The due date must be a valid date.',
            'dueDate.after_or_equal' => 'The due date must be today or a future date.',

            'status.required' => 'The status field is required.',
            'status.in' => 'The status must be one of the following: pending, is_progress, completed.',

            'imagePath.string' => 'The image path must be a string.',

            'priority.required' => 'The priority field is required.',
            'priority.in' => 'The priority must be one of the following: low, medium, high.',

            'projectId.required' => 'The project ID field is required.',
            'projectId.int' => 'The project ID must be an integer.',
            'projectId.exists' => 'The selected project does not exist.',
        ];
    }
}
