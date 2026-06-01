<?php

namespace App\Filament\Resources\MembershipTiers\Schemas;

use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MembershipTierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->required(),
                TextInput::make('name_en')
                    ->required(),
                TextInput::make('name_ar')
                    ->required(),
                Textarea::make('description_en')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('description_ar')
                    ->required()
                    ->columnSpanFull(),
                // Stored as JSON arrays (model casts features_en/features_ar to array).
                // TagsInput keeps them as arrays — a Textarea would save a string and
                // corrupt the cast (each feature = one tag; press Enter to add).
                TagsInput::make('features_en')
                    ->label('Features (English)')
                    ->placeholder('Add a feature')
                    ->columnSpanFull(),
                TagsInput::make('features_ar')
                    ->label('Features (Arabic)')
                    ->placeholder('أضف ميزة')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
