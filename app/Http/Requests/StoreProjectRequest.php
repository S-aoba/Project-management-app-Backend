<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'name' => 'required|string|max:255|unique:projects,name',
            'description' => 'nullable|max:1000',
            'due_date' => 'required|date|after:today',
            'status' => 'required|in:pending,is_progress,completed',
            'image_path' => 'nullable|string', 
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
            'image_path.string' => '所定のフォーマットに従ってください'
        ];
    }
}
