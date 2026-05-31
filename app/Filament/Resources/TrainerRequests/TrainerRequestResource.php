<?php

namespace App\Filament\Resources\TrainerRequests;

use App\Filament\Resources\TrainerRequests\Pages\CreateTrainerRequest;
use App\Filament\Resources\TrainerRequests\Pages\EditTrainerRequest;
use App\Filament\Resources\TrainerRequests\Pages\ListTrainerRequests;
use App\Filament\Resources\TrainerRequests\Schemas\TrainerRequestForm;
use App\Filament\Resources\TrainerRequests\Tables\TrainerRequestsTable;
use App\Models\TrainerRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TrainerRequestResource extends Resource
{
    protected static ?string $model = TrainerRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TrainerRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TrainerRequestsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTrainerRequests::route('/'),
            'create' => CreateTrainerRequest::route('/create'),
            'edit' => EditTrainerRequest::route('/{record}/edit'),
        ];
    }
}
