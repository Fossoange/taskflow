<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Voir la liste des tâches.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Voir une tâche.
     */
    public function view(User $user, Task $task): bool
    {
        return $task->project->user_id === $user->id;
    }

    /**
     * Créer une tâche.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Modifier une tâche.
     */
    public function update(User $user, Task $task): bool
    {
        return $task->project->user_id === $user->id;
    }

    /**
     * Supprimer une tâche.
     */
    public function delete(User $user, Task $task): bool
    {
        return $task->project->user_id === $user->id;
    }

    /**
     * Restaurer une tâche.
     */
    public function restore(User $user, Task $task): bool
    {
        return $task->project->user_id === $user->id;
    }

    /**
     * Supprimer définitivement une tâche.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return $task->project->user_id === $user->id;
    }
}