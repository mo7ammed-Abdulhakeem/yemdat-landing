<?php

namespace App\Filament\Resources\EmailBroadcasts;

use App\Filament\Resources\EmailBroadcasts\Pages\CreateEmailBroadcast;
use App\Filament\Resources\EmailBroadcasts\Pages\EditEmailBroadcast;
use App\Filament\Resources\EmailBroadcasts\Pages\ListEmailBroadcasts;
use App\Filament\Resources\EmailBroadcasts\Schemas\EmailBroadcastForm;
use App\Filament\Resources\EmailBroadcasts\Tables\EmailBroadcastsTable;
use App\Models\EmailBroadcast;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class EmailBroadcastResource extends Resource
{
    protected static ?string $model = EmailBroadcast::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'subject_en';

    protected static ?string $navigationLabel = 'Broadcasts';

    // Gate the whole resource on the `broadcasts` permission (super_admins pass via hasPermission()).
    protected static function userCanManage(): bool
    {
        return (bool) (auth()->user()?->hasPermission('broadcasts'));
    }

    public static function canViewAny(): bool
    {
        return static::userCanManage();
    }

    public static function canCreate(): bool
    {
        return static::userCanManage();
    }

    public static function canEdit(Model $record): bool
    {
        return static::userCanManage();
    }

    public static function canDelete(Model $record): bool
    {
        return static::userCanManage();
    }

    public static function canDeleteAny(): bool
    {
        return static::userCanManage();
    }

    public static function form(Schema $schema): Schema
    {
        return EmailBroadcastForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmailBroadcastsTable::configure($table);
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
            'index' => ListEmailBroadcasts::route('/'),
            'create' => CreateEmailBroadcast::route('/create'),
            'edit' => EditEmailBroadcast::route('/{record}/edit'),
        ];
    }
}
