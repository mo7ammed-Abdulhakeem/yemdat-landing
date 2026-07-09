<?php

namespace App\Filament\Resources\Members\Tables;

use App\Filament\Resources\Members\MemberResource;
use App\Filament\Resources\Members\Pages\ViewMember;
use App\Models\Member;
use App\Models\MembershipTier;
use App\Models\Specialty;
use App\Support\CsvExport;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class MembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn ($query) => $query->with(['specialtyOption', 'user']))
            ->columns([
                TextColumn::make('full_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('membership_type')
                    ->label('Membership')
                    ->badge()
                    ->sortable(),
                TextColumn::make('trainer_status')
                    ->label('Trainer')
                    ->badge()
                    ->color('warning')
                    ->getStateUsing(fn (Member $record) => $record->isTrainer() ? 'Trainer' : null)
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('country')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('specialty')
                    ->label('University Major')
                    ->formatStateUsing(fn ($state, $record) => $record->specialty_label)
                    ->toggleable(),
                TextColumn::make('phone_number')
                    ->label('Phone')
                    ->formatStateUsing(fn ($state, $record) => trim(($record->phone_code ? $record->phone_code . ' ' : '') . $state))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('email_verified_at')
                    ->label('Verified')
                    ->dateTime()
                    ->placeholder('Not verified')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('unsubscribed_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('membership_type')
                    ->label('Membership')
                    ->options(fn () => MembershipTier::orderBy('sort_order')->pluck('name_en', 'slug')),
                SelectFilter::make('gender')
                    ->options(['Male' => 'Male', 'Female' => 'Female']),
                SelectFilter::make('specialty')
                    ->label('University Major')
                    ->options(fn () => Specialty::ordered()->pluck('name_en', 'slug')),
                TernaryFilter::make('email_verified_at')
                    ->label('Email verified')
                    ->nullable(),
                TernaryFilter::make('unsubscribed_at')
                    ->label('Unsubscribed')
                    ->placeholder('All')
                    ->trueLabel('Unsubscribed only')
                    ->falseLabel('Subscribed only')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('unsubscribed_at'),
                        false: fn ($query) => $query->whereNull('unsubscribed_at'),
                        blank: fn ($query) => $query,
                    ),
            ])
            ->recordUrl(fn ($record): string => MemberResource::getUrl('view', ['record' => $record]))
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    ViewMember::makeTrainerAction(),
                    ViewMember::resendTrainerInviteAction(),
                    ViewMember::revokeTrainerAction(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    self::exportBulkAction(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function exportBulkAction(): BulkAction
    {
        return BulkAction::make('export')
            ->label('Export selected')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('gray')
            ->action(fn ($records) => CsvExport::download(
                'members-'.now()->format('Y-m-d').'.csv',
                ['Full name', 'Email', 'Membership', 'Gender', 'Phone', 'Country', 'University Major', 'Education', 'LinkedIn', 'Bio', 'Email verified at', 'Unsubscribed at', 'Joined'],
                $records->map(fn (Member $m) => [
                    $m->full_name,
                    $m->email,
                    $m->membership_type,
                    $m->gender,
                    trim(($m->phone_code ? $m->phone_code.' ' : '').$m->phone_number),
                    $m->country,
                    $m->specialty_label,
                    $m->education_level,
                    $m->linkedin_url,
                    $m->bio,
                    $m->email_verified_at,
                    $m->unsubscribed_at,
                    $m->created_at,
                ]),
            ))
            ->deselectRecordsAfterCompletion();
    }
}
