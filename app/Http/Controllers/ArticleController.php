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
    public function show(Request $request, $slug) {
        $this->context += [
            'article' => $article = Article::firstWhere('slug', $slug) ?? abort(404),
            'page_title' => PageHelper::makeSeoTitle($article->title, 17)
        ];

        return view('frontend.articles.default', $this->context);
    }
}
