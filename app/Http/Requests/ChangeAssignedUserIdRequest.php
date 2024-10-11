<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class ChangeAssignedUserIdRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $projcetId = $this->route('task')['project_id'];
        $project = Project::find($projcetId);

        if (!$project) {
            return false;
        }

        $assignedUserId = $this->route('task')['assigned_user_id'];
        if (!$this->user()->isAdmin($project) && $this->user()->id !== $assignedUserId) {
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
            'newAssignedUserId' => 'required|int|exists:users,id'
        ];
    }

    public function messages(): array
    {
        return [
            'newAssignedUserId.required' => 'The new assigned user ID field is required.',
            'newAssignedUserId.int' => 'The new assigned user ID must be an integer.',
            'newAssignedUserId.exists' => 'The selected new assigned user does not exist.',
        ];
    }

}
