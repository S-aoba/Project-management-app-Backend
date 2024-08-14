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
            'name' => 'required|string|max:255|unique:projects,name',
            'description' => 'nullable|max:1000',
            'due_date' => 'required|date|after:today',
            'status' => 'required|in:pending,is_progress,completed',
            'image_path' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'assigned_user_id' => 'required|int|exists:users,id',
            'project_id' => 'required|int|exists:projects,id'
        ];
    }

    public function messages(): array
    {
        return [
            // 文言については要件等
            'name.required' => 'Project名は必須項目です',
            'name.string' => 'Project名には文字列を入力してください',
            'name.max' => 'Project名の最大文字数は255文字です',
            'name.unique' => 'プロジェクト名は既に存在しています',
            'description.max' => 'ProjectのDescriptionの最大文字数は1000文字です',
            'due_date.required' => '締日は必ず設定してください',
            'due_date.date' => '締日の日付のフォーマットが正しくありません',
            'due_date.after' => '締日の日付は、今日以降に設定してください',
            'status.required' => 'ステータス項目は必須です',
            'status.in' => '決められた項目の中で選択してください',
            'image_path.string' => '所定のフォーマットに従ってください',
            'priority.required' => '優先度項目は必須です',
            'priority.in' => '決められた項目の中で選択してください',
            'assigned_user_id.required' => 'assigned_user_idは必須項目です',
            'assigned_user_id.int' => 'assigned_user_idは数値のみです',
            'assigned_user_id.exists' => 'userは存在しません',
            'project_id.required' => 'project_idは必須項目です',
            'project_id.int' => 'project_idは数値のみです',
            'project_id.exists' => 'projectは存在しません',
        ];
    }
}
