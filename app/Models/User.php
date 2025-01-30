<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    // Add role constants
    public const ROLE_GUEST = 'guest';
    public const ROLE_SUBSCRIBER = 'subscriber';
    public const ROLE_THEME_RESPONSIBLE = 'theme_responsible';
    public const ROLE_EDITOR = 'editor';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active'
    ];

    // Add this method to get the current role
    public function getRoleAttribute()
    {
        static $roles = [];
        
        if (!isset($roles[$this->id])) {
            $roles[$this->id] = $this->roles()->first()?->name ?? 'guest';
        }
        
        return $roles[$this->id];
    }

    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => $role]);
        }
        $this->syncRoles($role);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    public function responsibleThemes()
    {
        return $this->hasMany(Theme::class, 'responsible_id');
    }

    public function themeSubscriptions()
    {
        return $this->belongsToMany(Theme::class, 'theme_subscriptions')
                    ->withTimestamps();
    }

    /**
     * Get all possible roles
     */
    public static function getRoles(): array
    {
        return ['guest', 'subscriber', 'theme_responsible', 'editor'];
    }

    /**
     * Get browsing history
     */
    public function browsingHistory()
    {
        return $this->hasMany(BrowsingHistory::class);
    }

    /**
     * Get user comments
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get user ratings
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Get the articles that the user has viewed.
     */
    public function viewedArticles()
    {
        return $this->belongsToMany(Article::class, 'article_user')
            ->withTimestamps()
            ->withPivot('last_viewed_at');
    }

    /**
     * Check if user is subscribed to a theme
     */
    public function isSubscribedTo(Theme $theme): bool
    {
        return $this->themeSubscriptions()->where('theme_id', $theme->id)->exists();
    }

    /**
     * Check if user can moderate a theme
     */
    public function canModerateTheme(Theme $theme): bool
    {
        return $this->hasRole('editor') || 
            ($this->hasRole('theme_responsible') && $this->id === $theme->responsible_id);
    }

    /**
     * Get user's browsing history for articles
     */
    public function getRecentlyViewed(int $limit = 5)
    {
        return $this->browsingHistory()
            ->with(['article.theme', 'article.author'])
            ->orderBy('last_visited_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recommended articles based on user's interests
     */
    public function getRecommendedArticles(int $limit = 3)
    {
        $subscribedThemeIds = $this->themeSubscriptions()->pluck('themes.id');
        
        return Article::whereIn('theme_id', $subscribedThemeIds)
            ->where('status', 'published')
            ->where('is_active', true)
            ->whereNotIn('id', $this->browsingHistory()->pluck('article_id'))
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function isEditor(): bool
    {
        return $this->hasRole('editor');
    }

    public function isThemeResponsible(): bool
    {
        return $this->hasRole('theme_responsible');
    }

    public function isSubscriber(): bool
    {
        return $this->hasRole('subscriber');
    }

    public function isGuest(): bool
    {
        return $this->hasRole('guest');
    }
    public function subscribedThemes()
    {
        return $this->belongsToMany(Theme::class, 'theme_subscriptions');
    }
}

