<?php

namespace App\Filament\Resources\Articles\Tables;

use App\Filament\Resources\Articles\Pages\ArticlePreview;
use App\Models\Article;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('hero_image'),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable(),
                // 💡 Виртуальная колонка
                TextColumn::make('type_label')
                    ->label('Type')
                    ->sortable(false)
                    // ->badge()
                    /* ->color(fn (string $state): string => match ($state) {
                        '📝 Blog Article' => 'info',
                        '📘 Travel Guide' => 'success',
                        '🧭 Itinerary' => 'warning',
                        default => 'gray',
                    }), */
                    ->getStateUsing(function (Article $article) {
                        $type = $article->itinerary ? 'itinerary' : 'default';
                        return $type;
                    })
                    ->formatStateUsing(function ($state, $record) {
                        return match ($state) {
                            'default' => '📝 Blog Article',
                            'itinerary' => '🧭 Itinerary',
                            // 'guide' => '📘 Travel Guide',
                            default => '❓ Unknown',
                        };
                    })
                    ->sortable(query: function ($query, string $direction) {
                        return $query->orderByRaw("
                            CASE
                                WHEN EXISTS (
                                    SELECT 1
                                    FROM article_itineraries
                                    WHERE article_itineraries.article_id = articles.id
                                ) THEN 'Itinerary'
                                ELSE 'Blog Article'
                            END $direction
                        ");
                    }),
                TextColumn::make('categories.title')
                    ->label('Categories')
                    ->badge()
                    ->color('primary')
                    ->separator(', '),
                TextColumn::make('comments_count')
                    ->label('Comments*')
                    // ->badge()
                    ->alignCenter()
                    ->counts('comments')
                    ->sortable(),
                    // ->getStateUsing(fn (Article $article) => $article->comments?->count() . ' comments'),
                IconColumn::make('pinned')
                    ->boolean()
                    ->sortable(),
                IconColumn::make('published')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('pinned')
                    ->toggle()
                    ->query(fn ($query) => $query->where('pinned', true))
                    ->label('Pinned'),
                Filter::make('published')
                    ->toggle()
                    ->query(fn ($query) => $query->where('published', true))
                    ->label('Published'),

                SelectFilter::make('categories')
                    ->relationship('categories', 'title')
                    ->label('Category')
                    ->multiple(),
                SelectFilter::make('article_type')
                    ->label('Type')
                    ->options([
                        'default'   => 'Default',
                        'itinerary' => 'Itinerary',
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['value'] === 'regular') {
                            return $query->whereDoesntHave('itinerary');
                        }

                        if ($data['value'] === 'itinerary') {
                            return $query->whereHas('itinerary');
                        }

                        return $query;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
//                Action::make('preview')
//                    ->label('Preview')
//                    ->icon('heroicon-o-eye')
//                    ->url(fn ($record) => route(ArticlePreview::getRouteName(), ['record' => $record->id]))
//                    ->openUrlInNewTab(),
                ActionGroup::make([
                    Action::make('go_to')
                        ->label('Go to')
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->url(fn ($record) => route('articles.show', (['id' => $record->id, 'slug' => $record->slug])))
                        ->openUrlInNewTab(),
                    Action::make('preview')
                        ->label('Preview')
                        ->icon('heroicon-o-eye')
                        ->disabled()
                        ->modalHeading('Article Preview')
                        ->modalWidth('7xl')
                        ->modalContent(function ($record) {
                            return view('admin.article-preview-modal', [
                                'url' => route(ArticlePreview::getRouteName(), ['record' => $record->id]),
                            ]);
                        })
                        ->modalSubmitAction(false)
                        ->modalCancelAction(false),
                ]),// ->button()


                /* Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Article Preview')
                    ->modalSubmitAction(false) // спрятать кнопку submit
                    ->modalCancelAction(false) // спрятать cancel
                    ->modalWidth('full')        // широкая модалка
                    ->modalContent(function (Article $record) {
                        $url = route('articles.show', (['id' => $record->id, 'slug' => $record->slug]));

                        return view('admin.article-preview-modal', [
                            'url' => $url,
                        ]);
                    }), */
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
