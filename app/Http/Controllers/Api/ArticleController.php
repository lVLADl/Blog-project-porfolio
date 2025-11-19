<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    protected $context = [];
    protected $resource;

    public function __construct()
    {
        parent::__construct();
        $this->resource = Article::class;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->resource::all());
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        // $record = $this->resource::findOrFail($id);
        $record = $this->resource::find($id);
        if(is_null($record)) {
            return response()->json(['message' => 'Record not found.'], 404);
        }

        !(request()->has('showCategories')) ?: $record->load('categories');
        // $record->load('itinerary');
        !(request()->has('showComments')) ?: $record->load('comments');
        return response()->json($record);
    }
    public function store(StoreArticleRequest $request): \Illuminate\Http\JsonResponse|null
    {
        $record = $this->resource::create($request->validated());
        $record->categories()->sync($request->input('categories', []));
        $file = $request->file('hero_image');
        if ($file) {
            $path = $file->store($record->hero_image_dir, 'public');
            $record->hero_image = $path;
            // $record->force(['hero_image' => $newPath])->save();
        }
        if($record->save()) {
            return response()->json([
                'message' => 'Record created successfully.',
                'data' => $record
            ], 201);
        }

        return response()->json(['message' => 'Failed to create record.'], 500);
    }

    public function update(UpdateArticleRequest $request, $id): \Illuminate\Http\JsonResponse|null
    {
        $record = $this->resource::find($id);
        if(is_null($record)) {
            return response()->json(['message' => 'Record not found.'], 404);
        }

        $record->update($request->validated());
        $record->categories()->sync($request->input('categories'));

        $file = $request->file('hero_image');
        if ($file) {
            $path = $file->store($record->hero_image_dir, 'public');
            $record->hero_image = $path;
            // $record->force(['hero_image' => $newPath])->save();
            $record->save();
        }


        return response()->json([
            'message' => 'Record updated successfully.',
            'data' => $record
        ]);
    }

    public function destroy(Model|Article $record): \Illuminate\Http\JsonResponse|null
    {
        $record->categories()->detach();
        Storage::disk('public')->delete($record->hero_image);
        $record->delete();
        return response()->json(['message' => 'Record deleted successfully.'], 204);
    }

}
