<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Post Content')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title_en')
                            ->label('Title (English)')
                            ->required(),
                        TextInput::make('title_ar')
                            ->label('Title (Arabic)')
                            ->required(),
                        TextInput::make('slug')
                            ->required()
                            ->columnSpanFull()
                            ->helperText('URL-friendly identifier, e.g. welcome-to-yemdat. Lowercase, words separated by hyphens.'),
                        Textarea::make('content_en')
                            ->label('Content (English)')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('content_ar')
                            ->label('Content (Arabic)')
                            ->required()
                            ->columnSpanFull(),
                        Select::make('type')
                            ->options(['announcement' => 'Announcement', 'update' => 'Update', 'article' => 'Article'])
                            ->default('article')
                            ->required()
                            ->helperText('Controls how the post is labelled and grouped on the public site.'),
                        FileUpload::make('image')
                            ->image()
                            ->helperText('Cover image for the post. Recommended 1200×630px (landscape).'),
                        TagsInput::make('tags')
                            ->default(null)
                            ->columnSpanFull()
                            ->helperText('Optional. Type a keyword and press Enter to add it, e.g. python, data, training.'),
                    ]),

                Section::make('Publishing')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_published')
                            ->required()
                            ->helperText('Only published posts appear on the public site.'),
                        Toggle::make('is_featured')
                            ->required()
                            ->helperText('Featured posts are highlighted on the home page.'),
                    ]),
            ]);
    }
}
