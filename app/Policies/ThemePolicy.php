<?php

namespace App\Policies;

use App\Models\Theme;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThemePolicy
{
    use HandlesAuthorization;

    public function manage(User $user, Theme $theme): bool
    {
        return $user->hasRole('editor') || 
            ($user->hasRole('theme_responsible') && $user->id === $theme->responsible_id);
    }

    public function review(User $user, Theme $theme): bool
    {
        return $this->manage($user, $theme);
    }

    public function moderate(User $user, Theme $theme): bool
    {
        return $this->manage($user, $theme);
    }
}
