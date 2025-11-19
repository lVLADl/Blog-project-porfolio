<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'slug' => 'unique:articles,slug|string|max:100',
            'title' => 'string|max:255',
            'description' => 'string',
            'body' => 'sometimes|nullable|string',
            'hero_title' => 'sometimes|nullable|string',
            'hero_image' => 'sometimes|nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'published' => 'boolean',
            'pinned' => 'boolean',

            'categories' => 'sometimes|array',
            'categories.*' => 'integer|exists:categories,id|distinct:strict',
        ];
    }
}
