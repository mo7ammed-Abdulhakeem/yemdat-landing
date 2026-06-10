<?php

namespace App\Filament\Resources\EmailBroadcasts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class EmailBroadcastInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Performance')
                    ->columns(4)
                    ->schema([
                        TextEntry::make('total_recipients')
                            ->label('Recipients')
                            ->numeric()
                            ->weight(FontWeight::Bold),
                        TextEntry::make('opens_count')
                            ->label('Opened')
                            ->numeric(),
                        TextEntry::make('open_rate')
                            ->label('Open rate')
                            ->suffix('%'),
                        TextEntry::make('unsubscribes_count')
                            ->label('Unsubscribed')
                            ->numeric(),
                    ]),

                Section::make('Details')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('audience_label')
                            ->label('Audience')
                            ->badge(),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn ($state) => match ($state) {
                                'sent' => 'success',
                                'sending' => 'warning',
                                default => 'gray',
                            }),
                        TextEntry::make('language')->badge(),
                        TextEntry::make('sent_at')
                            ->dateTime()
                            ->placeholder('—'),
                        TextEntry::make('creator.name')
                            ->label('Sent by')
                            ->placeholder('—'),
                    ]),

                Section::make('Content')
                    ->schema([
                        TextEntry::make('subject_en')
                            ->label('Subject (English)')
                            ->placeholder('—'),
                        TextEntry::make('subject_ar')
                            ->label('Subject (Arabic)')
                            ->placeholder('—'),
                        TextEntry::make('body_en')
                            ->label('Body (English)')
                            ->html()
                            ->placeholder('—')
                            ->columnSpanFull(),
                        TextEntry::make('body_ar')
                            ->label('Body (Arabic)')
                            ->html()
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
