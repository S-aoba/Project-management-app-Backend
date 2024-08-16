<?php

namespace App\Models;

use App\Http\Requests\StoreProjectRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Project extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'description',
        'due_date',
        'status',
        'image_path',
        'created_by',
        'updated_by'
    ];

    static function createProjectAndAssignAdmin(array $validatedData) {
        try {
            DB::beginTransaction();

            $newProject = Project::create([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'due_date' => $validatedData['due_date'],
                'status' => $validatedData['status'],
                'image_path' => $validatedData['image_path'],
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);
            
            $role = Role::where('name', 'Admin')->first();
            ProjectUser::create([
                'project_id' => $newProject->id,
                'user_id' => Auth::id(),
                'role_id' => $role->id
            ]);
                        
            DB::commit();

            return $newProject;
        
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Project creation failed: ' . $e->getMessage());

            throw $e;
        }
    }

    public function users()
    {
        // 関係: Project 多対多 User
        return $this->belongsToMany(User::class, 'project_users');
    }

    public function roles()
    {
        // 関係: Project 多対多 Role
        return $this->belongsToMany(Role::class, 'project_users');
    }

    public function tasks()
    {
        // 関係: Project 一対多 Task
        return $this->hasMany(Task::class);
    }
}
