<?php

namespace App\Http\Requests;

use App\Models\ProjectUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class ShowProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // 閲覧するユーザーがProjectに参加しているかどうか
        $userId = Auth::id();
        $projectId = $this->route('project')->id;
        
        $isJoined = ProjectUser::where('project_id', $projectId)
                        ->where('user_id', $userId)
                        ->exists();

        if(!$isJoined) {
            throw new HttpResponseException(response()->json([
                'message' => 'このプロジェクトへのアクセス権限がありません。',
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
