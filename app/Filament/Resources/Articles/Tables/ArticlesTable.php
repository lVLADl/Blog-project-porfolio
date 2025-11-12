<?php

namespace App\Filament\Resources\Articles\Tables;

use App\Models\Article;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
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
                    }),
                TextColumn::make('comments')
                    ->label('Comments*')
                    // ->sortable()
                    ->getStateUsing(fn (Article $article) => $article->comments?->count() . ' comments'),
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
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
