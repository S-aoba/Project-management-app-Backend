<?php

namespace App\Http\Requests;

use App\Models\ProjectUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class InviteMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // アクションをするユーザーがProjectのAdminであること
        $userId = Auth::id();
        $projectId = $this->route('project_id');

        $isAdmin = ProjectUser::where('project_id', $projectId)
                            ->where('user_id', $userId)
                            ->where('role_id', 1)
                            ->exists();

        if(!$isAdmin) {
            throw new HttpResponseException(response()->json([
                'message' => 'この操作は管理者のみが行うことができます。',
            ], 422));
        }

        // JoinするユーザーがPtojectには未参加であること
        $joinUserId = $this->input('user_id');
        
        $isJoinedProject = ProjectUser::where('project_id', $projectId)
                                    ->where('user_id', $joinUserId)
                                    ->exists();

        if($isJoinedProject) {
            throw new HttpResponseException(response()->json([
                'message' => '招待されたユーザーは既にプロジェクトに参加しています。',
            ], 422));
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
            'user_id' => 'required|integer|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'user_idは必須項目です',
            'user_id.integer' => 'user_idは数値型のみ有効です',
            'user_id.exists' => 'userが存在しません'
        ];
    }
}
