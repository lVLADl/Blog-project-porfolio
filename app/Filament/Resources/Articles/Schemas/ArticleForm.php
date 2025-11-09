<?php

namespace App\Filament\Resources\Articles\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->required(),
                Toggle::make('pinned')
                    ->required(),
                Toggle::make('published')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('description')
                    ->required(),
                FileUpload::make('hero_image')
                    ->image(),
                TextInput::make('hero_title'),
                Textarea::make('body')
                    ->columnSpanFull(),
            ]);
    }
}
