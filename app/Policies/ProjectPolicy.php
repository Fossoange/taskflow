<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Voir la liste des projets.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Voir un projet.
     */
    public function view(User $user, Project $project): bool
    {
        return $project->user_id === $user->id;
    }

    /**
     * Créer un projet.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Modifier un projet.
     */
    public function update(User $user, Project $project): bool
    {
        return $project->user_id === $user->id;
    }

    /**
     * Supprimer un projet.
     */
    public function delete(User $user, Project $project): bool
    {
        return $project->user_id === $user->id;
    }

    /**
     * Restaurer un projet.
     */
    public function restore(User $user, Project $project): bool
    {
        return $project->user_id === $user->id;
    }

    /**
     * Supprimer définitivement un projet.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return $project->user_id === $user->id;
    }
}