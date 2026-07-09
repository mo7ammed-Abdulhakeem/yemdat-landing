<?php

namespace App\Filament\Resources\Members\Schemas;

use App\Models\MembershipTier;
use App\Models\Specialty;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Account')
                    ->columns(2)
                    ->schema([
                        TextInput::make('full_name')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        // Password is only written when the admin actually types one, so editing a
                        // member never wipes/overwrites their existing password. The model's
                        // `hashed` cast handles hashing; do NOT hash here (would double-hash).
                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->columnSpanFull()
                            ->helperText('Leave blank to keep the current password.'),
                    ]),

                Section::make('Contact')
                    ->columns(2)
                    ->schema([
                        TextInput::make('phone_code')
                            ->tel()
                            ->required(),
                        TextInput::make('phone_number')
                            ->tel()
                            ->required(),
                        TextInput::make('country')
                            ->required(),
                        Select::make('gender')
                            ->options(['Male' => 'Male', 'Female' => 'Female'])
                            ->native(false),
                    ]),

                Section::make('Profile')
                    ->columns(2)
                    ->schema([
                        Select::make('membership_type')
                            ->label('Membership')
                            ->options(fn () => MembershipTier::orderBy('sort_order')->pluck('name_en', 'slug'))
                            ->searchable()
                            ->required(),
                        TextInput::make('education_level')
                            ->default(null),
                        Select::make('specialty')
                            ->label('University Major')
                            ->options(fn () => Specialty::active()->ordered()->pluck('name_en', 'slug'))
                            ->searchable()
                            ->live()
                            ->required(),
                        TextInput::make('specialty_other')
                            ->label('University Major (other detail)')
                            ->visible(fn ($get) => $get('specialty') === 'other')
                            ->default(null),
                        TextInput::make('linkedin_url')
                            ->label('LinkedIn profile')
                            ->url()
                            ->default(null),
                        Textarea::make('bio')
                            ->default(null)
                            ->columnSpanFull(),
                    ]),

                Section::make('Status')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('email_verified_at')
                            ->label('Email verified at'),
                        DateTimePicker::make('unsubscribed_at'),
                    ]),
            ]);
    }
}
