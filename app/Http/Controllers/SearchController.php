<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller {
    private static $pagination_per_page = 12;
    public function __construct()
    {
        parent::__construct();
        $this->context['page_type'] = 'search';
    }
    public function index(Request $request)
    {
        $query = Article::query()
            ->with(['categories'])
            ->where('published', true)
            ->orderBy('pinned', 'desc')
            ->orderBy('created_at', 'desc');
            // ->whereNotNull('published_at'); // если нужно

        // === Поиск по названию ===
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        // === Поиск по тегам (categories) ===
        if ($request->filled('categories')) {
            $categoryIds = $request->categories;

            $query->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        }

        $per_page = static::$pagination_per_page;
        $this->context['pagination_page'] = $pagination_page = $request->input('page', 1);
        $this->context['pagination_page_count']  = ceil($query->count() / $per_page);
        $offset = ($pagination_page - 1) * $per_page;

        if ( $pagination_page > $this->context['pagination_page_count'] ) {
            // return redirect()->route('index');
        }

        // $this->context['articles'] = $articles = $query->paginate(12)->withQueryString();
        $this->context['articles'] = $query
            ->skip($offset)
            ->take($per_page)
            ->get();
        $this->context['categories'] = $categories = Category::orderBy('title')->get();

        return view('frontend.search', $this->context);
    }

    public function searchAjax(Request $request) {
        $query = trim($request->get('q', ''));

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $articles = Article::where('title', 'like', "%$query%")
            ->limit(8)
            ->get(['id', 'slug', 'title']);

        return response()->json($articles);
    }
}
