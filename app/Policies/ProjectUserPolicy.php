<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectUserPolicy
{  
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function inviteMember(User $user, Project $project): bool
    {
        return $user->isAdmin($project);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function removeMember(User $user, Project $project): bool
    {
        return $user->isAdmin($project);
    }

    public function changeRole(User $user, Project $project): bool
    {
        return $user->isAdmin($project);
    }
}
