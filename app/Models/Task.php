<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'due_date',
        'status',
        'priority',
        'image_path',
        'created_by',
        'updated_by',
        'assigned_user_id',
        'project_id'
    ];
}
