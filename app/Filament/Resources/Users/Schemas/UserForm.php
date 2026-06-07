<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Placeholder;
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
                        'trainer' => 'Trainer',
                    ])
                    ->default('admin')
                    ->required()
                    ->live()
                    ->helperText('Super Admins bypass all permission checks. Trainers only access the separate /trainer dashboard (usually created via "Make trainer" on a member).'),
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
                    // Permissions only gate the /admin panel; trainers can't enter it,
                    // so hide the field for them to avoid the (misleading) impression
                    // that ticking boxes grants anything in /trainer.
                    ->visible(fn ($get) => $get('role') !== 'trainer')
                    ->helperText('Ignored for Super Admins (they have full access).'),
                Placeholder::make('trainer_permissions_note')
                    ->label('Permissions')
                    ->content("Trainers don't use these permissions — they sign in to the separate /trainer dashboard, which only shows the events they're assigned to teach and those events' certificates.")
                    ->columnSpanFull()
                    ->visible(fn ($get) => $get('role') === 'trainer'),
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
