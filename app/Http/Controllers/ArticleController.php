<?php

namespace App\Http\Controllers;

use App\Helpers\PageHelper;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->context['page_type'] = 'article';
    }

    # puteshestvie-po-karpatam-magiya-gor-i-prirody
    public function show(Request $request, $id, $slug) {
        $this->context += [
            'article' => $article = Article::firstWhere('id', $id) ?? abort(404),
            'page_title' => PageHelper::makeSeoTitle($article->title, 17)
        ];
        if($article->itinerary) {
            $this->context['itinerary'] = $article->itinerary;
            return view('frontend.articles.article_itinerary', $this->context);
        }

        return view('frontend.articles.default', $this->context);
    }
}
