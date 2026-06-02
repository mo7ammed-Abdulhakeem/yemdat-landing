<?php

namespace App\Filament\Resources\Members\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class MemberInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Member')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('full_name')
                            ->label('Full name')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('email')
                            ->label('Email address')
                            ->copyable()
                            ->url(fn ($record) => $record->email ? 'mailto:' . $record->email : null),
                        TextEntry::make('phone_number')
                            ->label('Phone number')
                            ->copyable()
                            ->formatStateUsing(fn ($state, $record) => trim(($record->phone_code ? $record->phone_code . ' ' : '') . $state))
                            ->placeholder('—'),
                        TextEntry::make('gender')
                            ->label('Gender')
                            ->placeholder('—'),
                        TextEntry::make('country')
                            ->label('Country')
                            ->placeholder('—'),
                        TextEntry::make('education_level')
                            ->label('Education level')
                            ->placeholder('—'),
                        TextEntry::make('linkedin_url')
                            ->label('LinkedIn profile')
                            ->url(fn ($record) => $record->linkedin_url, shouldOpenInNewTab: true)
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ]),

                Section::make('Membership')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('membership_type')
                            ->label('Membership')
                            ->badge()
                            ->color('info')
                            ->placeholder('—'),
                        TextEntry::make('events_count')
                            ->label('Registered events')
                            ->getStateUsing(fn ($record) => $record->events()->count()),
                        TextEntry::make('specialty')
                            ->label('Specialty')
                            ->formatStateUsing(fn ($state, $record) => $record->specialty_label)
                            ->placeholder('—'),
                        TextEntry::make('bio')
                            ->label('Bio')
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ]),

                Section::make('Account')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('email_verified_at')
                            ->label('Email verified')
                            ->dateTime('F j, Y, g:i A')
                            ->placeholder('Not verified'),
                        TextEntry::make('created_at')
                            ->label('Joined')
                            ->dateTime('F j, Y'),
                        TextEntry::make('unsubscribed_at')
                            ->label('Unsubscribed')
                            ->dateTime('F j, Y')
                            ->placeholder('Subscribed'),
                    ]),
            ]);
    }
}
