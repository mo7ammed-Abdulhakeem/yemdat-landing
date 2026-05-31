<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                Select::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'super_admin' => 'Super Admin',
                    ])
                    ->default('admin')
                    ->required()
                    ->helperText('Super Admins bypass all permission checks.'),
                CheckboxList::make('permissions')
                    ->options([
                        'members' => 'Members',
                        'messages' => 'Messages',
                        'events' => 'Events',
                        'posts' => 'Posts',
                        'trainers' => 'Trainer Requests',
                        'broadcasts' => 'Broadcasts',
                        'analytics' => 'Analytics',
                        'settings' => 'Settings',
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->helperText('Ignored for Super Admins (they have full access).'),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    // Required on create; on edit, leaving it blank keeps the current password.
                    // The User model's `hashed` cast handles hashing.
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn ($state): bool => filled($state))
                    ->helperText('Leave blank when editing to keep the current password.'),
            ]);
    }
}
