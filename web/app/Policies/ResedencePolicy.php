<?php

namespace App\Policies;

use App\Models\User;

namespace App\Policies;

use App\Models\User;
use App\Models\Post;

class ResedencePolicy
{
    /**
     * Determine if the given post can be viewed by the user.
     */
    public function view(User $user, Post $post)
    {
        // Define your logic to determine if the user can view the post
        return $user->id === $post->user_id;
    }

    /**
     * Determine if the given post can be created by the user.
     */
    public function create(User $user)
    {
        // Define your logic to determine if the user can create a post
        return true; // Allow all users to create a post
    }

    /**
     * Determine if the given post can be updated by the user.
     */
    public function update(User $user, User $resedence)
    {
        // Define your logic to determine if the user can update the post
        dd($user);
        dd($resedence);
    }

    /**
     * Determine if the given post can be deleted by the user.
     */
    public function delete(User $user, Post $post)
    {
        // Define your logic to determine if the user can delete the post
        return $user->id === $post->user_id;
    }
}
