<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Event Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title_en')
                            ->label('Title (English)')
                            ->required(),
                        TextInput::make('title_ar')
                            ->label('Title (Arabic)')
                            ->required(),
                        TextInput::make('slug')
                            ->required()
                            ->columnSpanFull()
                            ->helperText('URL-friendly identifier, e.g. data-bootcamp-2026. Lowercase, words separated by hyphens.'),
                        Textarea::make('description_en')
                            ->label('Description (English)')
                            ->default(null)
                            ->columnSpanFull(),
                        Textarea::make('description_ar')
                            ->label('Description (Arabic)')
                            ->default(null)
                            ->columnSpanFull(),
                        DateTimePicker::make('start_date')
                            ->required(),
                        DateTimePicker::make('end_date')
                            ->helperText('Leave empty for a single-moment event.'),
                        TextInput::make('location')
                            ->default(null)
                            ->helperText('Physical venue, or leave empty for an online event.'),
                        TextInput::make('join_url')
                            ->url()
                            ->default(null)
                            ->helperText('Online meeting link (Zoom, Google Meet, …) shown to registered members.'),
                        FileUpload::make('image')
                            ->image()
                            ->columnSpanFull()
                            ->helperText('Event cover image. Recommended 1200×630px (landscape).'),
                        Toggle::make('is_active')
                            ->required()
                            ->columnSpanFull()
                            ->helperText('Only active events are listed on the public site.'),
                    ]),

                Section::make('Lecturer')
                    ->columns(2)
                    ->schema([
                        TextInput::make('lecturer_name_en')
                            ->default(null),
                        TextInput::make('lecturer_name_ar')
                            ->default(null),
                        TextInput::make('lecturer_title_en')
                            ->default(null),
                        TextInput::make('lecturer_title_ar')
                            ->default(null),
                        FileUpload::make('lecturer_image')
                            ->image()
                            ->columnSpanFull(),
                        TextInput::make('lecturer_linkedin')
                            ->url()
                            ->default(null)
                            ->columnSpanFull()
                            ->helperText("Full URL to the lecturer's LinkedIn profile."),
                    ]),
            ]);
    }
}
