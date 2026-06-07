<?php

namespace App\Filament\Concerns;

use Filament\Actions\Action;
use Filament\Support\Enums\IconPosition;
use Illuminate\Database\Eloquent\Model;

/**
 * Adds "Previous" / "Next" header actions to a resource record page
 * (ViewRecord / EditRecord), letting admins step through records without
 * returning to the list.
 *
 * Navigation follows primary-key order to match the newest-first lists:
 * "Previous" = the next-newer record, "Next" = the next-older record.
 * It deliberately ignores the active table filters/sort (a documented
 * limitation kept for simplicity and predictability).
 *
 * The host page must implement navPage() returning the resource page name
 * to link to ('view' or 'edit').
 */
trait HasRecordNavigation
{
    abstract protected function navPage(): string;

    protected function previousRecordAction(): Action
    {
        return Action::make('previousRecord')
            ->label('Previous')
            ->icon('heroicon-o-chevron-left')
            ->color('gray')
            ->url(fn (): ?string => $this->adjacentRecordUrl('previous'))
            ->hidden(fn (): bool => $this->adjacentRecordUrl('previous') === null);
    }

    protected function nextRecordAction(): Action
    {
        return Action::make('nextRecord')
            ->label('Next')
            ->icon('heroicon-o-chevron-right')
            ->iconPosition(IconPosition::After)
            ->color('gray')
            ->url(fn (): ?string => $this->adjacentRecordUrl('next'))
            ->hidden(fn (): bool => $this->adjacentRecordUrl('next') === null);
    }

    protected function adjacentRecordUrl(string $direction): ?string
    {
        /** @var Model $record */
        $record = $this->getRecord();
        $keyName = $record->getKeyName();
        $key = $record->getKey();

        $adjacent = $record->newQuery()
            ->when(
                $direction === 'previous',
                fn ($q) => $q->where($keyName, '>', $key)->orderBy($keyName, 'asc'),
                fn ($q) => $q->where($keyName, '<', $key)->orderBy($keyName, 'desc'),
            )
            ->first();

        if (! $adjacent) {
            return null;
        }

        return static::getResource()::getUrl($this->navPage(), ['record' => $adjacent]);
    }
}
