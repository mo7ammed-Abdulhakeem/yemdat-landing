<?php

namespace App\Filament\Resources\TrainerRequests\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TrainerRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('phone_number')
                    ->tel()
                    ->default(null),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('program_type')
                    ->default(null),
                TextInput::make('duration_hours')
                    ->numeric()
                    ->default(null),
                TextInput::make('duration_days')
                    ->numeric()
                    ->default(null),
                Textarea::make('agenda')
                    ->default(null)
                    ->columnSpanFull(),
                Toggle::make('agreed_to_free_provision')
                    ->required(),
                TextInput::make('linkedin_url')
                    ->url()
                    ->default(null),
            ]);
    }
}
