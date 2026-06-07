<?php

namespace App\Filament\Resources\EmailTemplates\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class EmailTemplateInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Settings')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('mailable_class')
                            ->label('Email type')
                            ->weight(FontWeight::Bold),
                        IconEntry::make('is_active')
                            ->label('Active')
                            ->boolean(),
                        TextEntry::make('from_email')
                            ->label('From email')
                            ->placeholder('Default sender'),
                        TextEntry::make('placeholders')
                            ->label('Available placeholders')
                            ->state(fn ($record) => collect(EmailTemplateForm::mailables()[$record->mailable_class] ?? [])
                                ->map(fn ($t) => '{'.$t.'}')
                                ->implode('  '))
                            ->placeholder('—'),
                    ]),

                Section::make('English')
                    ->schema([
                        TextEntry::make('subject_en')->label('Subject'),
                        TextEntry::make('body_en')->label('Body')->html()->columnSpanFull(),
                    ]),

                Section::make('Arabic')
                    ->schema([
                        TextEntry::make('subject_ar')->label('Subject'),
                        TextEntry::make('body_ar')->label('Body')->html()->columnSpanFull(),
                    ]),
            ]);
    }
}
