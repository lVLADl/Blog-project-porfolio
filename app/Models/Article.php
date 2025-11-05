<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';
    protected $fillable = ['slug', 'title', 'description', 'body', 'hero_title', 'hero_image', 'published', 'pinned'];
    public function categories() {
        return $this->belongsToMany('App\Models\Category', 'article_category', 'article_id', 'category_id')->withTimestamps();
    }
}
