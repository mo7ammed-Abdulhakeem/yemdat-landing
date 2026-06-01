<?php

namespace App\Filament\Resources\Certificates\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class CertificateInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Certificate')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('serial')
                            ->label('Serial')
                            ->copyable()
                            ->weight(FontWeight::Bold),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->state(fn ($record) => $record->revoked_at ? 'Revoked' : 'Valid')
                            ->color(fn ($state) => $state === 'Valid' ? 'success' : 'danger'),
                        TextEntry::make('type')
                            ->label('Type')
                            ->badge(),
                        TextEntry::make('issued_at')
                            ->label('Issued at')
                            ->dateTime('F j, Y, g:i A'),
                        TextEntry::make('issuer.name')
                            ->label('Issued by')
                            ->placeholder('—'),
                        TextEntry::make('revoked_at')
                            ->label('Revoked at')
                            ->dateTime('F j, Y, g:i A')
                            ->placeholder('—'),
                    ]),

                Section::make('Recipient & event')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('member.full_name')
                            ->label('Member')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('member.email')
                            ->label('Email')
                            ->copyable(),
                        TextEntry::make('event.title_en')
                            ->label('Event (EN)'),
                        TextEntry::make('event.title_ar')
                            ->label('Event (AR)'),
                    ]),
            ]);
    }
}
