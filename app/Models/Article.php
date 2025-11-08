<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ArticleComment;

class Article extends Model
{
    protected $table = 'articles';
    protected $fillable = ['slug', 'title', 'description', 'body', 'hero_title', 'hero_image', 'published', 'pinned'];
    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany('App\Models\Category', 'article_category', 'article_id', 'category_id')->withTimestamps();
    }
    public function itinerary(): Article|\Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne('App\Models\ArticleItinerary');
    }
    public function comments(): Article|\Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ArticleComment::class);
    }
}
