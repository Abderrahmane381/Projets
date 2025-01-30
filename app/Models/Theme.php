<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;
    protected $table = 'themes'; // Vérifiez que le nom de la table est correct

    protected $fillable = [
        'name',
        'description',
        'responsible_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'theme_subscriptions')
                    ->withTimestamps();
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function issues()
    {
        return $this->hasMany(Issue::class);
    }
}
