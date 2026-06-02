<?php

namespace App\Filament\Resources\Specialties\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class SpecialtyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    // Slug is the stable key members reference; lock it after creation.
                    ->disabledOn('edit')
                    ->dehydrated()
                    ->helperText('Stable key stored on members (e.g. "computer-science"). Cannot be changed after creation.'),
                TextInput::make('name_en')
                    ->label('Name (English)')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, $get, $set, string $operation) {
                        if ($operation === 'create' && blank($get('slug'))) {
                            $set('slug', Str::slug((string) $state));
                        }
                    }),
                TextInput::make('name_ar')
                    ->label('Name (Arabic)')
                    ->required(),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
            ]);
    }
}
