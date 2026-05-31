<?php

namespace App\Filament\Resources\EmailTemplates\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EmailTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('mailable_class')
                    ->required(),
                TextInput::make('from_email')
                    ->email()
                    ->default(null),
                TextInput::make('subject_en')
                    ->required(),
                TextInput::make('subject_ar')
                    ->required(),
                Textarea::make('body_en')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('body_ar')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('banner_image')
                    ->image(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
