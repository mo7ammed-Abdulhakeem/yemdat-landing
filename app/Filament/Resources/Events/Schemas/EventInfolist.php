<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

/**
 * Read-only event summary for the admin View page. The attendees/certificate
 * relation manager renders below this. Modeled on the trainer MyEventInfolist,
 * with admin-only fields (status, trainer, creator).
 */
class EventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Event')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('title_en')
                            ->label('Title (English)')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('title_ar')
                            ->label('Title (Arabic)')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('format')
                            ->badge(),
                        TextEntry::make('level')
                            ->badge()
                            ->placeholder('—'),
                        TextEntry::make('start_date')
                            ->dateTime('F j, Y, g:i A'),
                        TextEntry::make('end_date')
                            ->dateTime('F j, Y, g:i A')
                            ->placeholder('—'),
                        TextEntry::make('location')
                            ->placeholder('Online'),
                        TextEntry::make('join_url')
                            ->placeholder('—')
                            ->url(fn ($record) => $record->join_url, true),
                        TextEntry::make('description_en')
                            ->label('Description (English)')
                            ->html()
                            ->columnSpanFull()
                            ->placeholder('—'),
                        TextEntry::make('description_ar')
                            ->label('Description (Arabic)')
                            ->html()
                            ->columnSpanFull()
                            ->placeholder('—'),
                    ]),

                Section::make('Status & ownership')
                    ->columns(3)
                    ->schema([
                        IconEntry::make('is_active')
                            ->label('Active')
                            ->boolean(),
                        TextEntry::make('trainer.name')
                            ->label('Trainer')
                            ->placeholder('—'),
                        TextEntry::make('creator.name')
                            ->label('Created by')
                            ->placeholder('—'),
                    ]),

                Section::make('Lecturer (public display)')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('lecturer_name_en')
                            ->label('Name (English)')
                            ->placeholder('—'),
                        TextEntry::make('lecturer_name_ar')
                            ->label('Name (Arabic)')
                            ->placeholder('—'),
                        TextEntry::make('lecturer_title_en')
                            ->label('Title (English)')
                            ->placeholder('—'),
                        TextEntry::make('lecturer_title_ar')
                            ->label('Title (Arabic)')
                            ->placeholder('—'),
                    ]),
            ]);
    }
}
