<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        return $project->users->contains($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->isAdmin($project);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->isAdmin($project);
    }

    public function inviteMember(User $user, Project $project, int $joinUserId): bool
    {
        $isAdmin = $user->isAdmin($project);

        if(!$isAdmin) {
            return false;
        }

        $isJoinedProject = ProjectUser::isJoinedProject($project, $joinUserId);
        
        if($isJoinedProject) {
            return false;
        }
        
        return true;
    }
}
