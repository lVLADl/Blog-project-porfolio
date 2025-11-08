<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleCommentRequest;
use App\Models\ArticleComment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(StoreArticleCommentRequest $request): \Illuminate\Http\JsonResponse
    {
//        return response()->json([], 200);

        $data = $request->validated();
        $instance = ArticleComment::create($data);
        return response()->json($instance, 201);
    }
}
