<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'content',
        'featured_image',
        'view_count',
        'is_published'
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
    
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }
    
    public function isLikedByUser($userId = null): bool
    {
        $userId = $userId ?: Auth::id();
        
        if (!$userId) {
            return false;
        }
        
        return $this->likes()->where('user_id', $userId)->exists();
    }
    
    public function likesCount(): int
    {
        return $this->likes()->count();
    }
}
