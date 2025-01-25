<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Task;

class TaskPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(User $user, Task $task) {
        return $task->user_id === $user->id;
    }

    public function update(User $user, Task $task) {
        return $task->user_id === $user->id;
    }

    public function delete(User $user, Task $task) {
        return $task->user_id === $user->id;
    }

}
