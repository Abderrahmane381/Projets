<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'is_public',
        'is_active',
        'publication_date',
        'theme_id'
    ];

    protected $casts = [
        'publication_date' => 'datetime',
        'is_public' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }
}
