<?php

namespace App\Filament\Resources\TrainerRequests\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class TrainerRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Applicant')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')
                            ->label('Full name')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('country')
                            ->label('Country')
                            ->placeholder('—'),
                        TextEntry::make('email')
                            ->label('Email address')
                            ->copyable()
                            ->url(fn ($record) => $record->email ? 'mailto:' . $record->email : null),
                        TextEntry::make('phone_number')
                            ->label('Phone number')
                            ->copyable()
                            ->placeholder('—'),
                        TextEntry::make('linkedin_url')
                            ->label('LinkedIn profile')
                            ->url(fn ($record) => $record->linkedin_url, shouldOpenInNewTab: true)
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ]),

                Section::make('Program')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('program_type')
                            ->label('Program type')
                            ->badge()
                            ->color('info')
                            ->placeholder('Legacy request'),
                        TextEntry::make('duration_days')
                            ->label('Duration (days)')
                            ->suffix(' days')
                            ->placeholder('—'),
                        TextEntry::make('duration_hours')
                            ->label('Duration (hours)')
                            ->suffix(' hours')
                            ->placeholder('—'),
                        IconEntry::make('agreed_to_free_provision')
                            ->label('Agreed to free provision')
                            ->boolean(),
                    ]),

                Section::make('Agenda & details')
                    ->schema([
                        TextEntry::make('agenda')
                            ->hiddenLabel()
                            ->html()
                            ->placeholder('No details provided.')
                            ->columnSpanFull(),
                        TextEntry::make('created_at')
                            ->label('Submitted')
                            ->dateTime('F j, Y, g:i A'),
                    ]),
            ]);
    }
}
