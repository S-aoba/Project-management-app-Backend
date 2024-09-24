<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class StoreInviteCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // projectが存在してるかどうかの検証
        $projectId = $this->input('projectId');

        $project = Project::find($projectId);

        if (!$project) {
            return false;
        }

        // アクションを起こしたユーザーがProjectのadminであるかどうかの検証
        $isAdmin = $this->user()->isAdmin($project);
        if(!$isAdmin) {
            return false;
        }

        return true;
    }
}
