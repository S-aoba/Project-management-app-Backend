<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowInviteCodeRequest;
use App\Http\Requests\StoreInviteCodeRequest;
use App\Models\InviteCode;
use App\Models\ProjectUser;
use DateTime;
use Exception;

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
    public function show(ShowInviteCodeRequest $request, InviteCode $inviteCode)
    {
        try {
            // Invite codeの期限が切れていないか(期限を24時間に設定する)
            $createdAt = $inviteCode['created_at'];
            // 特定の日時を設定
            $specificDate = new DateTime($createdAt);
            // 現在の日時を取得
            $currentDate = new DateTime();
    
            // 差を計算
            $interval = $currentDate->diff($specificDate);
    
            // 24時間が経過していないかを判定
            if ($interval->days == 0 && $interval->h < 24) {
                $projectId = $inviteCode['project_id'];
                $userId = $request->input('userId');
                
                ProjectUser::addUserToProject($projectId, $userId, 2);
    
                return response()->json([
                    'message' => 'Invite successfully.'
                ]);
            } else {
                throw new Exception('This invite code is expired.', 410);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }  
}
