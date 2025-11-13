<?php

namespace App\Http\Controllers;

use App\Helpers\PageHelper;
use App\Models\Article;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    protected static $pagination_per_page = 6;
    public function __construct()
    {
        parent::__construct();
        $this->context['page_type'] = 'index';
        $this->context['page_title'] = PageHelper::makeSeoTitle();

    }

    public function index(Request $request) {
        $articles = Article::where('published', 1)
            ->orderBy('pinned', 'desc')
            ->orderBy('created_at', 'desc');

        $this->context['slider'] = $slider = Article::where('published', 1)->whereNotNull('hero_image')->inRandomOrder()->take(3)->get(['hero_image']);

        $per_page = static::$pagination_per_page;
        $this->context['pagination_page'] = $pagination_page = $request->input('page', 1);
        $this->context['pagination_page_count']  = ceil($articles->count() / $per_page);
        $offset = ($pagination_page - 1) * $per_page;

        if ( $pagination_page > $this->context['pagination_page_count'] ) {
            return redirect()->route('index');
        }

        $this->context['articles'] = $articles
            ->skip($offset)
            ->take($per_page)
            ->get();

        return view('frontend.index', $this->context);
    }
    public function dev(Request $request) {
        //
    }
}
