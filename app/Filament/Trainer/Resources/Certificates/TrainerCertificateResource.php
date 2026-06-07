<?php

namespace App\Filament\Trainer\Resources\Certificates;

use App\Filament\Resources\Certificates\Schemas\CertificateInfolist;
use App\Filament\Trainer\Resources\Certificates\Pages\ListMyCertificates;
use App\Filament\Trainer\Resources\Certificates\Pages\ViewMyCertificate;
use App\Filament\Trainer\Resources\Certificates\Tables\MyCertificatesTable;
use App\Models\Certificate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TrainerCertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    public static function getNavigationLabel(): string
    {
        return __('trainer.certificates');
    }

    /**
     * Only certificates for events this trainer teaches.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('event', fn (Builder $query) => $query->where('trainer_id', auth()->id()));
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function infolist(Schema $schema): Schema
    {
        return CertificateInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MyCertificatesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMyCertificates::route('/'),
            'view' => ViewMyCertificate::route('/{record}'),
        ];
    }
}
