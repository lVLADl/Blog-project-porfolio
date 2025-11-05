<?php

namespace App\Http\Controllers;

use App\Helpers\PageHelper;
use App\Models\Article;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->context['page_type'] = 'index';
        $this->context['page_title'] = PageHelper::makeSeoTitle();

    }

    public function index(Request $request) {
        $pinned_collection = Article::where('pinned', true)
                            ->where('published', 1)
                            ->orderBy('created_at', 'asc')->get();

        $main_collection = Article::where('published', 1)
            ->orWhere('pinned', true)
            ->orderBy('created_at', 'desc')/*->limit(9)*/->get();

        $this->context['articles'] = $pinned_collection->concat($main_collection)->unique();
        return view('frontend.index', $this->context);
    }
    public function dev(Request $request) {
        //
    }
}
