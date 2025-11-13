<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Flex;
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
    protected function customizeFormSchema(Schema $schema): Schema {
        $default_schema = [
            Group::make()
                ->schema([
                    Section::make('Main')
                        ->schema([
                            Forms\Components\TextInput::make('slug')
                                ->label('Slug')
                                ->suffixAction(
                                    Action::make('generateSlug')
                                        ->label('Generate')
                                        ->button()
                                        ->action(function ($livewire, callable $set) {
                                            $title = $livewire->data['title'] ?? null;
                                            if ($title) {
                                                $set('slug', \Str::slug($title));
                                            }
                                        })
                                )
                                ->required(),
                            Forms\Components\TextInput::make('title')
                                ->label('Index Page Title')
                                ->reactive()
                                ->debounce(800)
                                ->afterStateUpdated(fn ($state, callable $set) =>
                                $set('slug', Str::slug($state))
                                )
                                ->required(),
                            Forms\Components\Textarea::make('description')
                                ->label('Index Page Description')
                                ->autosize()
                                ->required(),
                        ]),

                    Section::make('Hero')
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
                        ]),

                    Section::make('Content')
                        ->schema([
                            Forms\Components\RichEditor::make('body')
                                ->toolbarButtons([
                                    ['bold',
                                        'italic',
                                        'underline',
                                        'strike',
                                        'subscript',
                                        'superscript',
                                        'h2',
                                        'h3',
                                        'bulletList',
                                        'orderedList',
                                        'link',
                                        'blockquote',],
                                    // 'codeBlock',
                                    // 'horizontalRule',
                                    'attachFiles',
                                    'undo',
                                    'redo'
                                ])
                                ->fileAttachmentsDisk('public')            // какой диск использовать для хранения изображений
                                ->fileAttachmentsDirectory("/articles/temp/body") // директория внутри диска
                                ->fileAttachmentsVisibility('public')      // публичная или приватная видимость
                                ->label('Body'),
                        ]),
                ])
                ->columnSpanFull(),
            Section::make('Meta')
                ->schema([
                    Forms\Components\Toggle::make('published')
                        ->label('Published')
                        ->default(true),
                    Forms\Components\Toggle::make('pinned')
                        ->label('Pinned'),

                    Forms\Components\Select::make('categories')
                        ->label('Categories')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->native(false)
                        ->relationship('categories', 'title'),
                ])
                ->aside()      // Filament v4 фича — ставит это справа красиво

            /* Flex::make([
                Section::make('Main')
                        ->schema([

                        ])
                Section::make('Meta')
                        ->schema([

                        ])
                )]
                Group::make()->schema([
                    // ===== LEFT SIDE: MAIN CONTENT (2/3 ширины) =====
                    Section::make('Basic Info')
                        ->schema([

                        ])
                        ->columns(1),

                    Section::make('Hero Section')
                        ->schema([

                        ])
                        ->columns(1),

                    Section::make('Content')
                        ->schema([

                        ])
                ])->grow(),
                // === ПРАВАЯ КОЛОНКА (сайдбар) ===
                Group::make()->schema([
                    Section::make('Status')
                        ->schema([

                        ])
                        ->columns(1),


                    Section::make('Tags')
                        ->schema([

                        ])
                        ->columns(1)
                    ])->grow(false),
                ]) */
        ];
        return $schema->schema([
            ...$default_schema,
        ]);
    }
//    public function form(Schema $schema): Schema {
//        $default_schema = [
//            Group::make()
//                ->schema([
//                    Section::make('Main')
//                        ->schema([
//                            Forms\Components\TextInput::make('slug')
//                                ->label('Slug')
//                                ->suffixAction(
//                                    Action::make('generateSlug')
//                                        ->label('Generate')
//                                        ->button()
//                                        ->action(function ($livewire, callable $set) {
//                                            $title = $livewire->data['title'] ?? null;
//                                            if ($title) {
//                                                $set('slug', \Str::slug($title));
//                                            }
//                                        })
//                                )
//                                ->required(),
//                            Forms\Components\TextInput::make('title')
//                                ->label('Index Page Title')
//                                ->reactive()
//                                ->debounce(800)
//                                ->afterStateUpdated(fn ($state, callable $set) =>
//                                $set('slug', Str::slug($state))
//                                )
//                                ->required(),
//                            Forms\Components\Textarea::make('description')
//                                ->label('Index Page Description')
//                                ->autosize()
//                                ->required(),
//                        ]),
//
//                    Section::make('Hero')
//                        ->schema([
//                            Forms\Components\TextInput::make('hero_title')
//                                ->label('Hero Title'),
//                            Forms\Components\FileUpload::make('hero_image')
//                                ->label('Hero Image')
//                                ->directory('articles/temp')
//                                ->disk('public')
//                                ->visibility('public')
//                                ->image()
//                                ->imageEditor()
//                                ->maxSize(4096),
//                        ]),
//
//                    Section::make('Content')
//                        ->schema([
//                            Forms\Components\RichEditor::make('body')
//                                ->toolbarButtons([
//                                    ['bold',
//                                        'italic',
//                                        'underline',
//                                        'strike',
//                                        'subscript',
//                                        'superscript',
//                                        'h2',
//                                        'h3',
//                                        'bulletList',
//                                        'orderedList',
//                                        'link',
//                                        'blockquote',],
//                                    // 'codeBlock',
//                                    // 'horizontalRule',
//                                    'attachFiles',
//                                    'undo',
//                                    'redo'
//                                ])
//                                ->fileAttachmentsDisk('public')            // какой диск использовать для хранения изображений
//                                ->fileAttachmentsDirectory("/articles/temp/body") // директория внутри диска
//                                ->fileAttachmentsVisibility('public')      // публичная или приватная видимость
//                                ->label('Body'),
//                        ]),
//                ])
//                ->columnSpanFull(),
//            Section::make('Meta')
//                ->schema([
//                    Forms\Components\Toggle::make('published')
//                        ->label('Published')
//                        ->default(true),
//                    Forms\Components\Toggle::make('pinned')
//                        ->label('Pinned'),
//
//                    Forms\Components\Select::make('categories')
//                        ->label('Categories')
//                        ->multiple()
//                        ->preload()
//                        ->searchable()
//                        ->native(false)
//                        ->relationship('categories', 'title'),
//                ])
//                ->aside()      // Filament v4 фича — ставит это справа красиво
//
//            /* Flex::make([
//                Section::make('Main')
//                        ->schema([
//
//                        ])
//                Section::make('Meta')
//                        ->schema([
//
//                        ])
//                )]
//                Group::make()->schema([
//                    // ===== LEFT SIDE: MAIN CONTENT (2/3 ширины) =====
//                    Section::make('Basic Info')
//                        ->schema([
//
//                        ])
//                        ->columns(1),
//
//                    Section::make('Hero Section')
//                        ->schema([
//
//                        ])
//                        ->columns(1),
//
//                    Section::make('Content')
//                        ->schema([
//
//                        ])
//                ])->grow(),
//                // === ПРАВАЯ КОЛОНКА (сайдбар) ===
//                Group::make()->schema([
//                    Section::make('Status')
//                        ->schema([
//
//                        ])
//                        ->columns(1),
//
//
//                    Section::make('Tags')
//                        ->schema([
//
//                        ])
//                        ->columns(1)
//                    ])->grow(false),
//                ]) */
//            ];
//        return $schema->schema([
//            ...$default_schema,
//        ]);
//    }
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
