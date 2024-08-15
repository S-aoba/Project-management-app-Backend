<?php

namespace App\Http\Requests;

use App\Models\ProjectUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class RemoveMemberRequest extends FormRequest
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

        // JoinするユーザーがPtojectに参加していること
        $joinUserId = $this->route('user_id');
        
        $isJoinedProject = ProjectUser::where('project_id', $projectId)
                                    ->where('user_id', $joinUserId)
                                    ->exists();

        if(!$isJoinedProject) {
            throw new HttpResponseException(response()->json([
                'message' => '削除対象のユーザーはProjectに存在しません',
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
            //
        ];
    }
}
