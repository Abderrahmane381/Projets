<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrowsingHistory extends Model
{
    use HasFactory;

    protected $table = 'browsing_history';

    protected $fillable = [
        'user_id',
        'article_id',
        'last_visited_at',
        'visit_count'
    ];

    protected $casts = [
        'last_visited_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
