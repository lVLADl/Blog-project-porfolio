<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleItinerary extends Model
{
    protected $table = 'article_itineraries';
    protected $fillable = ['article_id', 'intro', 'map_url', 'itinerary_days', 'trip_budget', 'trip_budget_advice', 'results_title', 'results_description'];
    protected $casts = [
        'itinerary_days' => 'array',
        'trip_budget' => 'array',
    ];

    public function article(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
