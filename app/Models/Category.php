<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    public $timestamps = false;
    protected $fillable = ['title', 'slug'];
    public function articles() {
        return $this
            ->belongsToMany('App\Models\Article',
                'article_category',
               'category_id',
               'article_id')
            ->withTimestamps();
    }
}
