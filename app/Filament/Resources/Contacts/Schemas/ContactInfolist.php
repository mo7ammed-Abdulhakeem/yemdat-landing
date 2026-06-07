<?php

namespace App\Filament\Resources\Contacts\Schemas;

use App\Models\Contact;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;

class ContactInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Sender')
                    ->description('Who sent this message')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')
                            ->label('Name')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn ($state) => Contact::statusOptions()[$state] ?? ucfirst((string) $state))
                            ->color(fn ($record) => $record->statusColor()),
                        TextEntry::make('member.full_name')
                            ->label('Registered member')
                            ->badge()
                            ->color('info')
                            ->placeholder('Guest (not a member)'),
                        TextEntry::make('email')
                            ->label('Email address')
                            ->copyable()
                            ->url(fn ($record) => $record->email ? 'mailto:' . $record->email : null),
                        TextEntry::make('phone_number')
                            ->label('Phone number')
                            ->copyable()
                            ->placeholder('—'),
                    ]),

                Section::make('Message')
                    ->schema([
                        TextEntry::make('subject')
                            ->label('Subject')
                            ->weight(FontWeight::Bold)
                            ->size(TextSize::Large)
                            ->columnSpanFull(),
                        TextEntry::make('message')
                            ->label('Message')
                            ->columnSpanFull(),
                        TextEntry::make('created_at')
                            ->label('Received')
                            ->dateTime('F j, Y, g:i A'),
                    ]),

                Section::make('Reply history')
                    ->visible(fn ($record) => $record->replies()->exists())
                    ->schema([
                        RepeatableEntry::make('replies')
                            ->hiddenLabel()
                            ->schema([
                                TextEntry::make('subject')
                                    ->weight(FontWeight::Bold)
                                    ->columnSpanFull(),
                                TextEntry::make('body')
                                    ->hiddenLabel()
                                    ->html()
                                    ->columnSpanFull(),
                                TextEntry::make('from_email')
                                    ->label('Sent from'),
                                TextEntry::make('created_at')
                                    ->label('Sent at')
                                    ->dateTime('F j, Y, g:i A'),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }
}
