<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Storage;

class EditArticle extends EditRecord
{
    protected static string $resource = ArticleResource::class;

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        // $type = $this->record?->type ?? 'default';
        $type = $this->record?->itinerary ? 'itinerary' : 'default';
        $default_schema = [
            // Внешняя сетка на 12 колонок — даёт точный контроль ширины
            Grid::make()->schema([

                // === ЛЕВАЯ КОЛОНКА (основной контент) ===
                Group::make()->schema([

                    Section::make('Basic Info')
                        ->schema([
                            Forms\Components\TextInput::make('slug')
                                ->label('Slug')
                                ->prefix('http://re-start-x2/articles/')
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
                        ->columns(0),

                    Section::make('Hero Section')
                        ->schema([
                            Forms\Components\TextInput::make('hero_title')
                                ->label('Hero Title'),
                            Forms\Components\FileUpload::make('hero_image')
                                ->label('Hero Image')
                                ->disk('public')
                                ->visibility('public')
                                ->directory(fn ($record) => "articles/{$record->id}/hero") # '/articles/hero_image'
                                ->image()
                                ->imageEditor()
                                ->maxSize(4096),
                        ])
                        ->columns(1),

                    ...(($type === 'default') ? [Section::make('Content')
                        ->schema([
                            Forms\Components\RichEditor::make('body')
                                ->fileAttachmentsDisk('public')            // какой диск использовать для хранения изображений
                                ->fileAttachmentsDirectory(fn ($record) => "articles/{$record->id}/body") // директория внутри диска
                                ->fileAttachmentsVisibility('public')      // публичная или приватная видимость
                                ->label('Body'),
                        ])] : []),
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


        return $schema
            ->schema(match ($type) {
                'itinerary' => [
                    ...$default_schema,
                    Section::make('Itinerary Details')
                        ->schema([
                            Group::make()
                                ->relationship('itinerary') // 👈 связь hasOne
                                ->schema([
                                    Forms\Components\Textarea::make('intro')
                                        ->label('Introduction-section Text'),
                                    Forms\Components\TextInput::make('map_url')
                                        ->label('Map URL')
                                        ->url(),
                                    Forms\Components\Repeater::make("itinerary_days.itinerary")
                                        ->label('Days')
                                        ->collapsible()
                                        ->defaultItems(1)
                                        ->addActionLabel('Add Day')
                                        ->schema([
                                            Forms\Components\TextInput::make('day')
                                                ->numeric()
                                                ->label('Day Number')
                                                ->required(),

                                            Forms\Components\TextInput::make('title')
                                                ->label('Day Title')
                                                ->required(),

                                            Forms\Components\Textarea::make('tip')
                                                ->label('Tip / Advice')
                                                ->rows(2),

                                            Fieldset::make('Image')
                                                ->columns(2)
                                                ->schema([
                                                    Forms\Components\TextInput::make('image.alt')
                                                        ->label('Alt text'),
                                                    // ✅ загрузка изображения в JSON
                                                    Forms\Components\FileUpload::make('image.src')
                                                        ->label('Image')
                                                        ->directory(fn ($record, $get) => "articles/{$record->article_id}/itinerary/day-{$get('day')}") # 'articles/itinerary'
                                                        ->disk('public')
                                                        ->visibility('public')
                                                        ->image()
                                                        ->imagePreviewHeight('120')
                                                        ->helperText('Загрузите изображение для этого дня'),
                                                ]),

                                            Forms\Components\Repeater::make('activities')
                                                ->label('Activities')
                                                ->simple(
                                                    Forms\Components\Textarea::make('activity')
                                                        ->rows(2)
                                                        ->label('Description')
                                                )
                                                ->collapsible()
                                                ->defaultItems(1),
                                        ])
                                        ->grid(1)
                                        ->columnSpanFull(),
                                    Forms\Components\Repeater::make('trip_budget.table.rows')
                                        ->label('Бюджет поездки')
                                        ->schema([
                                            Forms\Components\TextInput::make('Статья расходов')
                                                ->label('Статья расходов')
                                                ->required()
                                                ->placeholder('Проживание (3 ночи)'),
                                            Forms\Components\TextInput::make('Средняя стоимость')
                                                ->label('Средняя стоимость')
                                                ->placeholder('€180–250'),
                                        ])
                                        ->columns(2)
                                        ->defaultItems(0)
                                        ->addActionLabel('Добавить строку')
                                        ->columnSpanFull(),
                                    Forms\Components\Textarea::make('trip_budget_advice')
                                        ->label('Trip Budget: Advice Text'),
                                    Forms\Components\Textarea::make('results_title')
                                        ->label('Conclusion Section: Title'),
                                    Forms\Components\TextInput::make('results_description')
                                        ->label('Conclusion Section: Description'),
                                ]),
                        ])
                ],

                /* 'news' => [
                    Forms\Components\TextInput::make('title')->label('Headline')->required(),
                    Forms\Components\FileUpload::make('image')->label('Photo'),
                    Forms\Components\Textarea::make('body')->label('News Content')->required(),
                ], */

                default => $default_schema,
            });
    }
    protected function afterSave(): void
    {
        $article = $this->record;

        /*
         |--------------------------------------------------------------------------
         | Удаляем неиспользуемые inline-изображения из RichEditor
         |--------------------------------------------------------------------------
         */
        $bodyDir = "articles/{$article->id}/body";
        $disk = Storage::disk('public');

        // если папки нет — выходим
        if (!$disk->exists($bodyDir)) {
            return;
        }

        // 1️⃣ получаем все файлы из body/
        $storedFiles = collect($disk->files($bodyDir))
            ->map(fn($path) => basename($path))
            ->toArray();

        // 2️⃣ ищем, какие реально используются в HTML
        $usedFiles = [];
        if ($article->body) {
            preg_match_all('/src="[^"]*articles\/' . $article->id . '\/body\/([^"]+)"/i', $article->body, $matches);
            $usedFiles = $matches[1] ?? [];
        }

        // 3️⃣ сравниваем и удаляем лишние
        $unusedFiles = array_diff($storedFiles, $usedFiles);

        foreach ($unusedFiles as $filename) {
            $disk->delete("{$bodyDir}/{$filename}");
        }

        if (count($unusedFiles)) {
            info("🧹 Article {$article->id}: удалены неиспользуемые inline-файлы:", $unusedFiles);
        }
    }
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
