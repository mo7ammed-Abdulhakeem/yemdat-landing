<?php

namespace App\Filament\Trainer\Resources\Events;

use App\Filament\Resources\Events\RelationManagers\AttendeesRelationManager;
use App\Filament\Trainer\Resources\Events\Pages\ListMyEvents;
use App\Filament\Trainer\Resources\Events\Pages\ViewMyEvent;
use App\Filament\Trainer\Resources\Events\Schemas\MyEventInfolist;
use App\Filament\Trainer\Resources\Events\Tables\MyEventsTable;
use App\Models\Event;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TrainerEventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $recordTitleAttribute = 'title_en';

    protected static ?string $modelLabel = 'event';

    protected static ?string $pluralModelLabel = 'my events';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    public static function getNavigationLabel(): string
    {
        return __('trainer.my_events');
    }

    /**
     * Scope every query to the events this trainer is assigned to teach.
     * This also makes record resolution (view page) 404 for other trainers' events.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('trainer_id', auth()->id());
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function infolist(Schema $schema): Schema
    {
        return MyEventInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MyEventsTable::configure($table);
    }

    public static function getRelations(): array
    {
        // Registrations + mark attended/completed + issue/download certificates.
        // The issuing action records the trainer (auth user) as the certificate issuer.
        return [
            AttendeesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMyEvents::route('/'),
            'view' => ViewMyEvent::route('/{record}'),
        ];
    }
}
