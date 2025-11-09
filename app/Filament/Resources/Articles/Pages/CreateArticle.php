<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;
    public function form(Schema $schema): Schema {
        $default_schema = [
            // Внешняя сетка на 12 колонок — даёт точный контроль ширины
            Grid::make()->schema([

                // === ЛЕВАЯ КОЛОНКА (основной контент) ===
                Group::make()->schema([

                    Section::make('Basic Info')
                        ->schema([
                            Forms\Components\TextInput::make('slug')
                                ->label('Slug')
                                ->prefix('https://re-start-x2/articles/')
                                ->suffix('.com')
                                ->required(),
                            Forms\Components\TextInput::make('title')
                                ->label('Index Page Title')
                                ->required(),
                            Forms\Components\Textarea::make('description')
                                ->label('Index Page Description')
                                ->autosize()
                                ->required(),
                        ])
                        ->columns(1),

                    Section::make('Hero Section')
                        ->schema([
                            Forms\Components\TextInput::make('hero_title')
                                ->label('Hero Title'),
                            Forms\Components\FileUpload::make('hero_image')
                                ->label('Hero Image')
                                ->directory('articles/temp')
                                ->disk('public')
                                ->visibility('public')
                                ->image()
                                ->imageEditor()
                                ->maxSize(4096),
                        ])
                        ->columns(1),

                    Section::make('Content')
                        ->schema([
                            Forms\Components\RichEditor::make('body')
                                ->fileAttachmentsDisk('public')            // какой диск использовать для хранения изображений
                                ->fileAttachmentsDirectory("/articles/temp/body") // директория внутри диска
                                ->fileAttachmentsVisibility('public')      // публичная или приватная видимость
                                ->label('Body'),
                        ]),
                ])
                    // ширина колонки: на больших экранах 8/12, на средних и ниже — 12/12
                    ->columnSpan([
                        'lg' => 8,
                        'md' => 12,
                    ]),

                // === ПРАВАЯ КОЛОНКА (сайдбар) ===
                Group::make()->schema([

                    Section::make('Status')
                        ->compact() // меньше внутренних отступов, чтобы всё уместилось
                        ->schema([
                            Forms\Components\Toggle::make('published')
                                ->label('Published')
                                ->default(true),
                            Forms\Components\Toggle::make('pinned')
                                ->label('Pinned'),
                        ])
                        ->columns(1),

                    Section::make('Tags')
                        ->compact()
                        ->schema([
                            Forms\Components\Select::make('categories')
                                ->label('Categories')
                                ->multiple()
                                ->preload()
                                ->searchable()
                                ->native(false)
                                ->relationship('categories', 'title'),
                        ]),
                ])
                    // ширина сайдбара: 4/12 на больших экранах
                    ->columnSpan([
                        'lg' => 8,
                        'md' => 12,
                    ]),
                ]),
            ];
        return $schema->schema([
            ...$default_schema,
        ]);
    }
    protected function afterCreate(): void {
        $article = $this->record;

        /*
         * 1️⃣ HERO IMAGE
         * Перемещаем в articles/{id}/hero/
         */
        if ($path = $article->hero_image) {
            $filename = basename($path);
            $newPath = "articles/{$article->id}/hero/{$filename}";
            Storage::disk('public')->move($path, $newPath);
            $article->forceFill(['hero_image' => $newPath])->save();
        }

        /*
         *
         * 2️⃣ RickEditor Images (articles.body)
         *
         */
        if ($article->body && Str::contains($article->body, 'articles/temp/body')) {
            $html = $article->body;
            $matches = [];

            // Найдём все пути вида src="/storage/articles/temp/body/..."
            preg_match_all('/src="[^"]*articles\/temp\/body\/([^"]+)"/i', $html, $matches);

            if (!empty($matches[1])) {
                foreach ($matches[1] as $filename) {
                    $oldPath = "articles/temp/body/{$filename}";
                    $newPath = "articles/{$article->id}/body/{$filename}";

                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->makeDirectory("articles/{$article->id}/body");
                        Storage::disk('public')->move($oldPath, $newPath);

                        // заменим путь в HTML
                        $html = str_replace("articles/temp/body/{$filename}", $newPath, $html);
                    }
                }

                // обновляем тело статьи с новыми ссылками
                $article->update(['body' => $html]);
            }
        }
    }
}
