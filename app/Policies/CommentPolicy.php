<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id || 
               $user->hasRole('editor') || 
               ($user->hasRole('theme_responsible') && 
                $user->id === $comment->article->theme->responsible_id);
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $this->update($user, $comment);
    }

    public function moderate(User $user, Comment $comment)
    {
        // Check if user is editor or theme responsible for the article's theme
        return $user->isEditor() || 
            ($user->isThemeResponsible() && 
             $user->responsibleThemes->contains('id', $comment->article->theme_id));
    }
}
