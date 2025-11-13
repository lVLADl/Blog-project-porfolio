<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateItineraryArticle extends CreateRecord
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
                                // ->prefix('https://re-start-x2/articles/')
                                // ->suffix('.com')
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
                        ])
                        ->columns(1),

                    Section::make('Hero Section')
                        ->schema([
                            Forms\Components\TextInput::make('hero_title')
                                ->label('Hero Title'),
                            Forms\Components\FileUpload::make('hero_image')
                                ->label('Hero Image')
                                ->directory('articles/temp') # '/articles/hero_image'
                                ->image()
                                ->imageEditor()
                                ->disk('public')
                                ->visibility('public')
                                ->maxSize(4096),
                        ])
                        ->columns(1),
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
            Section::make('Itinerary Details')
                ->schema([
                    Group::make()
                        ->relationship('itinerary') // 👈 связь hasOne
                        ->schema([
                            Forms\Components\Textarea::make('intro')
                                ->label('Introduction')
                                ->autosize(),
                            Forms\Components\TextInput::make('map_url')
                                ->label('Map URL')
                                ->url(),

                            Forms\Components\Repeater::make("itinerary_days.itinerary")
                                ->label('Days')
                                ->collapsible()
                                ->defaultItems(0)
                                ->addActionLabel('Add Day')
                                ->schema([
                                    Forms\Components\TextInput::make('day')
                                        ->numeric()
                                        ->label('Day №')
                                        ->required(),

                                    Forms\Components\TextInput::make('title')
                                        ->label('Day Title')
                                        ->required(),

                                    Forms\Components\Textarea::make('tip')
                                        ->label('Tip/Advice')
                                        ->rows(2)
                                        ->autosize(),

                                    Fieldset::make('Image')
                                        ->columns(2)
                                        ->schema([
                                            Forms\Components\TextInput::make('image.alt')
                                                ->label('Alt text'),
                                            Forms\Components\FileUpload::make('image.src')
                                                ->image()
                                                ->imageEditor()
                                                ->directory('articles/temp/itinerary')
                                                ->disk('public')
                                                ->visibility('public'),
                                        ]),

                                    Forms\Components\Repeater::make('activities')
                                        ->label('Activities')
                                        ->simple(
                                            Forms\Components\Textarea::make('activity')
                                                ->rows(2)
                                                ->autosize()
                                                ->label('Description')
                                        )
                                        ->collapsible()
                                        ->defaultItems(1),
                                ])
                                ->grid(1)
                                ->columnSpanFull(),
                            Forms\Components\Repeater::make('trip_budget.table.rows')
                                ->label('Trip\'s Budget')
                                ->schema([
                                    Forms\Components\TextInput::make('Статья расходов')
                                        ->label('Expense Item')
                                        ->required()
                                        ->placeholder('Проживание (3 ночи)'),
                                    Forms\Components\TextInput::make('Средняя стоимость')
                                        ->label('Average Cost')
                                        ->placeholder('€180–250'),
                                ])
                                ->columns(2)
                                ->defaultItems(0)
                                ->addActionLabel('Add new expense')
                                ->columnSpanFull(),
                            Forms\Components\Textarea::make('trip_budget_advice')
                                ->autosize()
                                ->label('Trip\'s Budget: Tip'),
                            Forms\Components\Textarea::make('results_title')
                                ->label('Conclusion Section: Title'),
                            Forms\Components\TextInput::make('results_description')
                                ->label('Conclusion Section: Description'),
                        ]),
                ])
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
            $newPath  = "articles/{$article->id}/hero/{$filename}";
            Storage::disk('public')->move($path, $newPath);
            $article->forceFill(['hero_image' => $newPath])->save();
        }

        /*
         * 2️⃣ ITINERARY IMAGES
         * Загружаются в article_itinerary (через hasOne)
         * JSON хранится как {"itinerary": [...]}
         */
        $itinerary = $article->itinerary; // связь hasOne
        if (!$itinerary) return;

        $data = $itinerary->itinerary_days;

        // проверяем, что данные корректны
        if (!is_array($data) || !isset($data['itinerary']) || !is_array($data['itinerary'])) {
            return;
        }

        $updatedItinerary = $data; // скопируем исходный JSON

        foreach ($data['itinerary'] as $index => $day) {
            $dayNum = $day['day'] ?? ($index + 1);
            $src = data_get($day, 'image.src');

            // Если файл существует и лежит во временной папке
            if ($src && str_starts_with($src, 'articles/temp')) {
                $filename = basename($src);
                $newPath = "articles/{$article->id}/itinerary/day-{$dayNum}/{$filename}";

                Storage::disk('public')->makeDirectory("articles/{$article->id}/itinerary/day-{$dayNum}");
                Storage::disk('public')->move($src, $newPath);

                data_set($updatedItinerary, "itinerary.{$index}.image.src", $newPath);
            }
        }

        // сохраняем обновлённый JSON обратно в дочернюю модель
        $itinerary->update(['itinerary_days' => $updatedItinerary]);


    }
    /* protected function afterCreate(): void {
        $article = $this->record;

        if ($article->hero_image) {
            $oldPath = $article->hero_image;
            $filename = basename($oldPath);
            $newPath = "articles/{$article->id}/hero/{$filename}";

            Storage::disk('public')->move($oldPath, $newPath);
            $article->update(['hero_image' => $newPath]);
        }
    } */
}
