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
            throw new \Exception('User is already a member of this project.', 409);
        }

        return self::create([
            'project_id' => $projectId,
            'user_id' => $userId,
            'role_id' => $roleId,
        ]);
    }


    private static function updateUserRole(int $projectId, int $targetUserId, int $roleId): bool
    {
        return self::where('project_id', $projectId)
                    ->where('user_id', $targetUserId)
                    ->update(['role_id' => $roleId]) > 0;
    }

}

