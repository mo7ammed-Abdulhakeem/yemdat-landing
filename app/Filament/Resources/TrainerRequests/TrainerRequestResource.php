<?php

namespace App\Filament\Resources\TrainerRequests;

use App\Filament\Resources\TrainerRequests\Pages\ListTrainerRequests;
use App\Filament\Resources\TrainerRequests\Pages\ViewTrainerRequest;
use App\Filament\Resources\TrainerRequests\Schemas\TrainerRequestInfolist;
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

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserPlus;

    protected static string|\UnitEnum|null $navigationGroup = 'Community';

    protected static ?int $navigationSort = 2;

    public static function infolist(Schema $schema): Schema
    {
        return TrainerRequestInfolist::configure($schema);
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
            'view' => ViewTrainerRequest::route('/{record}'),
        ];
    }
}
