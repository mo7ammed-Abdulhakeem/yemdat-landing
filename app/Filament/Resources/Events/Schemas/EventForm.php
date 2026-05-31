<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('created_by')
                    ->numeric()
                    ->default(null),
                TextInput::make('title_en')
                    ->required(),
                TextInput::make('title_ar')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description_en')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('description_ar')
                    ->default(null)
                    ->columnSpanFull(),
                DateTimePicker::make('start_date')
                    ->required(),
                DateTimePicker::make('end_date'),
                TextInput::make('location')
                    ->default(null),
                TextInput::make('join_url')
                    ->url()
                    ->default(null),
                FileUpload::make('image')
                    ->image(),
                TextInput::make('lecturer_name_en')
                    ->default(null),
                TextInput::make('lecturer_name_ar')
                    ->default(null),
                TextInput::make('lecturer_title_en')
                    ->default(null),
                TextInput::make('lecturer_title_ar')
                    ->default(null),
                FileUpload::make('lecturer_image')
                    ->image(),
                TextInput::make('lecturer_linkedin')
                    ->default(null),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
