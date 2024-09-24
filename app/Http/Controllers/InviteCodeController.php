<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInviteCodeRequest;
use App\Models\InviteCode;
use Illuminate\Http\Request;

class InviteCodeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInviteCodeRequest $request)
    {
        try {
            // Invite codeの生成に成功
            $inviteCode = InviteCode::generateInviteCode();
            $projectId = $request->input('projectId');

            // Invite_code tableへデータを挿入
            InviteCode::create([
                'code' => $inviteCode,
                'project_id' => $projectId
            ]);

            return response()->json([
                'data' => $inviteCode,
                'message' => 'Generate invite code successfully.'
            ]);

        } catch (\Error $e) {
            // Invite codeの生成に失敗
            return response()->json([
                'error' => $e,
                'message' => 'Generate failed invite code. Please a little later, try again.'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, InviteCode $inviteCode)
    {
        return 'invited successfully.';
    }

    
}
