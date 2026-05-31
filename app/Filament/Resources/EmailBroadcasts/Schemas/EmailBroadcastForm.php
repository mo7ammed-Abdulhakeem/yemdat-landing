<?php

namespace App\Filament\Resources\EmailBroadcasts\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmailBroadcastForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('language')
                    ->options(['en' => 'English', 'ar' => 'Arabic'])
                    ->default('en')
                    ->required()
                    ->live(),
                Select::make('audience_type')
                    ->options(['all_members' => 'All Members', 'event_members' => 'Event Registrants'])
                    ->default('all_members')
                    ->required()
                    ->live(),
                Select::make('event_id')
                    ->label('Event')
                    ->relationship('event', 'title_en')
                    ->searchable()
                    ->visible(fn ($get) => $get('audience_type') === 'event_members')
                    ->required(fn ($get) => $get('audience_type') === 'event_members'),
                TextInput::make('subject_en')
                    ->label('Subject (English)')
                    ->maxLength(255)
                    ->visible(fn ($get) => $get('language') === 'en')
                    ->required(fn ($get) => $get('language') === 'en'),
                TextInput::make('subject_ar')
                    ->label('Subject (Arabic)')
                    ->maxLength(255)
                    ->visible(fn ($get) => $get('language') === 'ar')
                    ->required(fn ($get) => $get('language') === 'ar'),
                RichEditor::make('body_en')
                    ->label('Body (English)')
                    ->columnSpanFull()
                    ->visible(fn ($get) => $get('language') === 'en')
                    ->required(fn ($get) => $get('language') === 'en'),
                RichEditor::make('body_ar')
                    ->label('Body (Arabic)')
                    ->columnSpanFull()
                    ->visible(fn ($get) => $get('language') === 'ar')
                    ->required(fn ($get) => $get('language') === 'ar'),
                TextInput::make('from_email')
                    ->label('From Email (optional)')
                    ->email()
                    ->maxLength(255),
                TextInput::make('from_name')
                    ->label('From Name (optional)')
                    ->maxLength(255),
            ]);
    }
}
