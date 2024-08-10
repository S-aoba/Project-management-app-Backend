<?php

namespace App\Models;

use App\Http\Requests\StoreProjectRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    static function createProject(StoreProjectRequest $validatedData) {
        $new_project = Project::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'due_date' => $validatedData['due_date'],
            'status' => $validatedData['status'],
            'image_path' => $validatedData['image_path'],
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        $role = DB::table('roles')
                ->where('name', 'admin')
                ->first();

        $project_user = [
            'project_id' => $new_project->id,
            'user_id' => Auth::id(),
            'role_id' => $role->id
        ];

        DB::table('project_users')->insert($project_user);
    
        return $new_project;
    }

    public function users ()
    {
        // 関係: Project 多対多 User
        return $this->belongsToMany(User::class, 'project_users');
    }

    public function roles ()
    {
        // 関係: Project 多対多 Role
        return $this->belongsToMany(Role::class, 'project_users');
    }
}
