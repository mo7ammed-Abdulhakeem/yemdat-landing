<?php

namespace App\Filament\Resources\LearningPaths\Schemas;

use App\Models\Event;
use App\Models\Specialty;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class LearningPathForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Path Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title_en')
                            ->label('Title (English)')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $get, $set, string $operation) {
                                if ($operation === 'create' && blank($get('slug'))) {
                                    $set('slug', Str::slug((string) $state));
                                }
                            }),
                        TextInput::make('title_ar')
                            ->label('Title (Arabic)')
                            ->required(),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull()
                            ->helperText('URL-friendly identifier, e.g. start-your-data-career. Lowercase, words separated by hyphens.'),
                        TextInput::make('summary_en')
                            ->label('Summary (English)')
                            ->helperText('One-line teaser shown on path cards.'),
                        TextInput::make('summary_ar')
                            ->label('Summary (Arabic)'),
                        Textarea::make('description_en')
                            ->label('Description (English)')
                            ->columnSpanFull(),
                        Textarea::make('description_ar')
                            ->label('Description (Arabic)')
                            ->columnSpanFull(),
                        Select::make('level')
                            ->options([
                                'beginner' => 'Beginner',
                                'intermediate' => 'Intermediate',
                                'advanced' => 'Advanced',
                            ])
                            ->native(false),
                        Select::make('specialty')
                            ->label('Topic / Specialty')
                            ->options(fn () => Specialty::query()->orderBy('sort_order')->pluck('name_en', 'slug'))
                            ->searchable()
                            ->native(false),
                        TextInput::make('estimated_weeks')
                            ->label('Estimated duration (weeks)')
                            ->numeric()
                            ->minValue(1),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        FileUpload::make('image')
                            ->image()
                            ->disk('public')
                            ->directory('paths')
                            ->columnSpanFull()
                            ->helperText('Cover image for the path. Recommended 1200×630px (landscape).'),
                        Toggle::make('is_published')
                            ->columnSpanFull()
                            ->helperText('Only published paths appear on the public site.'),
                    ]),

                Section::make('Steps')
                    ->description('The ordered roadmap. Each step is either an existing Yemdat event or an external resource. Drag to reorder.')
                    ->schema([
                        Repeater::make('steps')
                            ->relationship()
                            ->orderColumn('sort_order')
                            ->reorderable()
                            ->collapsible()
                            ->cloneable()
                            ->itemLabel(fn (array $state): ?string => $state['title_en'] ?? 'Event step')
                            ->defaultItems(0)
                            ->columns(2)
                            ->schema([
                                Select::make('resource_type')
                                    ->options([
                                        'event' => 'Yemdat event (internal)',
                                        'video' => 'Video',
                                        'article' => 'Article',
                                        'course' => 'Course',
                                        'doc' => 'Document / dataset',
                                        'other' => 'Other',
                                    ])
                                    ->default('event')
                                    ->required()
                                    ->live()
                                    ->native(false),
                                Select::make('event_id')
                                    ->label('Event')
                                    ->options(fn () => Event::query()->orderBy('start_date', 'desc')->pluck('title_en', 'id'))
                                    ->searchable()
                                    ->visible(fn ($get) => $get('resource_type') === 'event')
                                    ->required(fn ($get) => $get('resource_type') === 'event'),
                                TextInput::make('title_en')
                                    ->label('Title (English)')
                                    ->visible(fn ($get) => $get('resource_type') !== 'event')
                                    ->required(fn ($get) => $get('resource_type') !== 'event'),
                                TextInput::make('title_ar')
                                    ->label('Title (Arabic)')
                                    ->visible(fn ($get) => $get('resource_type') !== 'event'),
                                TextInput::make('url')
                                    ->label('Link')
                                    ->url()
                                    ->columnSpanFull()
                                    ->visible(fn ($get) => $get('resource_type') !== 'event'),
                                TextInput::make('phase_en')
                                    ->label('Phase heading (English)')
                                    ->helperText('Optional group label, e.g. "Phase 1 — Foundations".'),
                                TextInput::make('phase_ar')
                                    ->label('Phase heading (Arabic)'),
                                Textarea::make('notes_en')
                                    ->label('Notes (English)')
                                    ->rows(2),
                                Textarea::make('notes_ar')
                                    ->label('Notes (Arabic)')
                                    ->rows(2),
                                Toggle::make('is_optional')
                                    ->label('Optional step')
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
