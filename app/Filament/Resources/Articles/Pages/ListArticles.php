<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use App\Filament\Resources\Categories\CategoryResource;
use App\Models\Category;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Filament\Forms;

class ListArticles extends ListRecords
{
    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([
                /* Actions\Action::make('createBlogDefault')
                    ->label('Create Blog Post')
                    ->model(\App\Models\Article::class)
                    ->icon('heroicon-o-pencil')
                    ->modalHeading('New Blog Post')
                    ->modalButton('Save Blog')
                    ->form([
                        Forms\Components\Toggle::make('pinned'),
                        Forms\Components\Toggle::make('published'),
                        Forms\Components\TextInput::make('slug')
                            ->prefix('http://re-start-x2/articles/')
                            ->suffix('.com')
                            ->required(),
                        // Forms\Components\FileUpload::make('hero_image'),
                        Forms\Components\TextInput::make('hero_image')
                            ->label('Hero Section Image')
                            ->url(),
                        Forms\Components\TextInput::make('hero_title')
                            ->label('Hero Section Title'),

                        Forms\Components\TextInput::make('title')
                            ->label('Index Page Title')
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label('Index Page Description')
                            ->required()
                            ->autosize(),

                        Forms\Components\RichEditor::make('body')
                            ->required(),


                        Forms\Components\Select::make('categories')
                            ->multiple()
                            ->searchable()
                            // ->relationship('categories', 'title')
                            ->options(\App\Models\Category::pluck('title', 'id'))
                            ->preload(),


                            /* ->getSearchResultsUsing(fn (string $search): array => Category::query()
                                    ->where('title', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->pluck('title', 'id')
                                    ->all())
                                ->getOptionLabelsUsing(fn (array $values): array => Category::query()
                                    ->whereIn('id', $values)
                                    ->pluck('title', 'id')
                                    ->all()), * /

                        /* Forms\Components\Repeater::make('categories')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->relationship('articles', 'title')
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                            ]), * /
                    ])
                    ->action(function (array $data) {
                        $article = \App\Models\Article::create([
                            'slug' => $data['slug'],
                            'title' => $data['title'],
                            'description' => $data['description'],
                            'body' => $data['body'],
                            'hero_title' => $data['hero_title'],
                            'hero_image' => $data['hero_image'],
                            'published' => $data['published'],
                            'pinned' => $data['pinned'],
                        ]);
                        if (!empty($data['categories'])) {
                            $article->categories()->sync($data['categories']);
                        }

                        // $this->notify('success', 'Blog post created successfully!');
                    }), */

                Actions\Action::make('createDefault')
                    ->label('Create Default Article')
                    ->icon('heroicon-o-pencil')
                    ->url(fn() => ArticleResource::getUrl('create-blog')),

                Actions\Action::make('createItinerary')
                    ->label('Create Travel Guide')
                    ->icon('heroicon-o-map')
                    ->url(fn() => ArticleResource::getUrl('create-itinerary'/*, ['type' => 'itinerary'] */)),
            ])
                ->label('Create Article')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->button()
        ];
    }
}
