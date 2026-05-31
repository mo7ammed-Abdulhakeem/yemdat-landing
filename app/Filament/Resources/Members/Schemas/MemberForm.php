<?php

namespace App\Filament\Resources\Members\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class MemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('full_name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->default(null),
                TextInput::make('otp_code')
                    ->default(null),
                DateTimePicker::make('otp_expires_at'),
                TextInput::make('gender')
                    ->default(null),
                TextInput::make('phone_code')
                    ->tel()
                    ->required(),
                TextInput::make('phone_number')
                    ->tel()
                    ->required(),
                TextInput::make('education_level')
                    ->default(null),
                TextInput::make('country')
                    ->required(),
                TextInput::make('specialty')
                    ->required(),
                TextInput::make('specialty_other')
                    ->default(null),
                TextInput::make('linkedin_url')
                    ->url()
                    ->default(null),
                TextInput::make('membership_type')
                    ->required(),
                Textarea::make('bio')
                    ->default(null)
                    ->columnSpanFull(),
                DateTimePicker::make('unsubscribed_at'),
            ]);
    }
}
