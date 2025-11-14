<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use App\Models\Article;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class ArticlePreview extends Page
{
    use InteractsWithRecord;

    protected static string $resource = ArticleResource::class;

    protected string $view = 'filament.resources.articles.pages.article-preview';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getTitle(): string
    {
        return 'Preview: ' . $this->record->title;
    }
}
