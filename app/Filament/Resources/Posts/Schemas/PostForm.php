<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title_en')
                    ->required(),
                TextInput::make('title_ar')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('content_en')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('content_ar')
                    ->required()
                    ->columnSpanFull(),
                Select::make('type')
                    ->options(['announcement' => 'Announcement', 'update' => 'Update', 'article' => 'Article'])
                    ->default('article')
                    ->required(),
                FileUpload::make('image')
                    ->image(),
                Textarea::make('tags')
                    ->default(null)
                    ->columnSpanFull(),
                Toggle::make('is_published')
                    ->required(),
                Toggle::make('is_featured')
                    ->required(),
                TextInput::make('created_by')
                    ->required()
                    ->numeric(),
            ]);
    }
}
