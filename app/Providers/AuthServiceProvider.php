<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Article;
use App\Models\Theme;
use App\Models\Comment;
use App\Policies\CommentPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // ...existing policies...
        Comment::class => CommentPolicy::class,
        
    ];

    public function boot(): void
    {
        
        Gate::define('role', function (User $user, string $role) {
            // Editor can access everything
            if ($user->isEditor()) {
                return true;
            }

            // Direct role check
            switch ($role) {
                case 'editor':
                    return $user->role === 'editor';
                case 'theme_responsible':
                    return $user->role === 'theme_responsible';
                case 'subscriber':
                    return $user->role === 'subscriber';
                default:
                    return false;
            }
        });

        Gate::define('moderate-theme', function (User $user, Theme $theme) {
            return $user->isEditor() || 
                   ($user->isThemeResponsible() && $theme->responsible_id === $user->id);
        });

        Gate::define('moderate-article', function (User $user, Article $article) {
            return $user->isEditor() || 
                   ($user->isThemeResponsible() && $article->theme->responsible_id === $user->id);
        });

        Gate::define('moderate-chat', function (User $user, Article $article) {
            return $user->isEditor() || 
                   ($user->isThemeResponsible() && $article->theme->responsible_id === $user->id);
        });
        
    }
    
}
