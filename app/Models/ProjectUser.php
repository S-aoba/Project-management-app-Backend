<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'role_id',
    ];
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role ()
    {
        return $this->belongsTo(Role::class);
    }

    private static function isUserAlreadyInProject(int $projectId, int $userId): bool
    {
        $exists = self::where('project_id', $projectId)
                      ->where('user_id', $userId)
                      ->exists();

        return $exists;
    }

    public static function addUserToProject($projectId, $userId, $roleId)
    {
        $exists = self::isUserAlreadyInProject($projectId, $userId);

        if ($exists) {
            throw new \Exception('User is already a member of this project.');
        }

        return self::create([
            'project_id' => $projectId,
            'user_id' => $userId,
            'role_id' => $roleId,
        ]);
    }

    public static function removeUserToProject($projectId, $userId)
    {
        if(Auth::id() === $userId) {
            throw new \Exception('Admin can not remove.');
        }

        $exists = self::isUserAlreadyInProject($projectId, $userId);

        if(!$exists) {
            throw new \Exception('User is not a member of this project.');
        }

        return self::where('project_id', $projectId)
                    ->where('user_id', $userId)
                    ->delete();
    }

    /**
     *  A: Admin, B:member, C:member, D:member
     *  ユースケース: 想定としては単純にRoleの変更のみなので、Adminであるユーザーを削除することはない
     *  AさんとBさんのロールを入れ替える
     */
    public static function changeUserRole($projectId, $targetUserId)
    {
        $adminUserId = Auth::id();

        if($adminUserId === $targetUserId) {
            throw new \Exception('Admin can not remove.');
        }

        $exists = self::isUserAlreadyInProject($projectId, $targetUserId);

        if(!$exists) {
            throw new \Exception('User is not a member of this project.');
        }

        DB::beginTransaction();

        try {
            self::updateUserRole($projectId, $adminUserId, 2);    
            self::updateUserRole($projectId, $targetUserId, 1);    
    
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

    private static function updateUserRole(int $projectId, int $targetUserId, int $roleId): bool
    {
        return self::where('project_id', $projectId)
                    ->where('user_id', $targetUserId)
                    ->update(['role_id' => $roleId]) > 0;
    }

}

