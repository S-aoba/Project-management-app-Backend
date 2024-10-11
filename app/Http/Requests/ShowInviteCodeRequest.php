<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class ShowInviteCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // 参加対象ユーザーが存在しているか。(DB上にユーザー登録されているか)
        $userId = $this->input('userId');
        $user = User::find($userId);

        if (!$user) {
            return false;
        }

        return true;
    }
}
