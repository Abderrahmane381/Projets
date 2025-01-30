<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'theme_id',
        'issue_id',
        'author_id',
        'status',
        'is_active'
    ];

    // Add validation rules as a static property
    public static $validationRules = [
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'theme_id' => 'required|exists:themes,id',
        'author_id' => 'required|exists:users,id',
        'status' => 'required|string',
        'is_active' => 'boolean'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING_REVIEW = 'pending_review';
    const STATUS_REJECTED = 'rejected';
   
    const STATUS_PUBLISHED = 'published';

    public static array $statuses = [
        self::STATUS_DRAFT,
        self::STATUS_PENDING_REVIEW,
        self::STATUS_REJECTED,
    
        self::STATUS_PUBLISHED
    ];

    protected $attributes = [
        'status' => self::STATUS_DRAFT,
        'is_active' => true
    ];

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    // Remove or comment out this method as we're using many-to-many now
    // public function issue()
    // {
    //     return $this->belongsTo(Issue::class);
    // }

    public function issues()
    {
        return $this->belongsToMany(Issue::class, 'article_issue');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function browsingHistory()
    {
        return $this->hasMany(BrowsingHistory::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->ratings()->avg('rating') ?? 0;
    }
}