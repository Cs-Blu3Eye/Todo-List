<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Tentukan apakah user dapat melihat model.
     */
    public function view(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }

    /**
     * Tentukan apakah user dapat membuat model.
     */
    public function create(User $user): bool
    {
        return true; // Semua user yang terautentikasi dapat membuat task
    }

    /**
     * Tentukan apakah user dapat memperbarui model.
     */
    public function update(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }

    /**
     * Tentukan apakah user dapat menghapus model.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }

    /**
     * Tentukan apakah user dapat mengembalikan model.
     */
    public function restore(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }

    /**
     * Tentukan apakah user dapat menghapus model secara permanen.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }
}

