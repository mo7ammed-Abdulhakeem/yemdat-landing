<?php

namespace App\Filament\Resources\Events\RelationManagers;

use App\Actions\Certificates\IssueCertificate;
use App\Models\Certificate;
use App\Services\CertificatePdf;
use App\Support\CsvExport;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AttendeesRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    protected static ?string $title = 'Attendees';

    /** Per-render cache of this event's certificates, keyed by member_id (avoids N+1 across rows). */
    protected ?\Illuminate\Support\Collection $certificateMap = null;

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('full_name')
            ->columns([
                TextColumn::make('full_name')
                    ->label('Member')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),
                IconColumn::make('attended')
                    ->label('Attended')
                    ->boolean()
                    ->getStateUsing(fn ($record) => (bool) $record->pivot->attended_at),
                IconColumn::make('completed')
                    ->label('Completed')
                    ->boolean()
                    ->getStateUsing(fn ($record) => (bool) $record->pivot->completed_at),
                TextColumn::make('certificate')
                    ->label('Certificate')
                    ->badge()
                    ->color(fn ($state) => $state === '—' ? 'gray' : 'success')
                    ->getStateUsing(fn ($record) => optional($this->certificateFor($record))->serial ?? '—'),
            ])
            ->headerActions([
                Action::make('exportAttendees')
                    ->label('Export CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        $event = $this->getOwnerRecord();

                        return CsvExport::download(
                            'attendees-'.Str::slug($event->title_en ?: 'event').'.csv',
                            ['Member', 'Email', 'Registered at', 'Attended at', 'Completed at'],
                            $event->members()->orderBy('full_name')->lazy()->map(fn ($member) => [
                                $member->full_name,
                                $member->email,
                                $member->pivot->created_at,
                                $member->pivot->attended_at,
                                $member->pivot->completed_at,
                            ]),
                        );
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('markAttended')
                        ->label('Mark attended')
                        ->icon('heroicon-o-user-circle')
                        ->visible(fn ($record) => ! $record->pivot->attended_at)
                        ->action(fn ($record) => $this->setPivot($record, ['attended_at' => now()])),
                    Action::make('markCompleted')
                        ->label('Mark completed')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->visible(fn ($record) => ! $record->pivot->completed_at)
                        ->action(fn ($record) => $this->setPivot($record, [
                            'attended_at' => $record->pivot->attended_at ?? now(),
                            'completed_at' => now(),
                        ])),
                    Action::make('unmarkCompleted')
                        ->label('Unmark completed')
                        ->icon('heroicon-o-x-mark')
                        ->color('gray')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => (bool) $record->pivot->completed_at)
                        ->action(fn ($record) => $this->setPivot($record, ['completed_at' => null])),
                    Action::make('issueCertificate')
                        ->label('Issue certificate')
                        ->icon('heroicon-o-academic-cap')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => $record->pivot->completed_at && ! $this->certificateFor($record))
                        ->action(function ($record) {
                            try {
                                $cert = app(IssueCertificate::class)->execute($this->getOwnerRecord(), $record, auth()->user());
                                $this->certificateMap = null; // refresh so the new cert shows
                                Notification::make()->title('Certificate issued')->body('Serial: '.$cert->serial)->success()->send();
                            } catch (\Throwable $e) {
                                Notification::make()->title('Could not issue certificate')->body($e->getMessage())->danger()->send();
                            }
                        }),
                    Action::make('downloadCertificate')
                        ->label('Download certificate')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('gray')
                        ->visible(fn ($record) => (bool) $this->certificateFor($record))
                        ->action(function ($record) {
                            $cert = $this->certificateFor($record);

                            return response()->streamDownload(
                                fn () => print (app(CertificatePdf::class)->render($cert)),
                                'certificate-'.$cert->serial.'.pdf',
                            );
                        }),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('issueCompleted')
                        ->label('Issue certificates (completed)')
                        ->icon('heroicon-o-academic-cap')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $issued = 0;
                            $skipped = 0;
                            foreach ($records as $record) {
                                try {
                                    // autoEmail: false — bulk issuance must not fire one
                                    // synchronous email per certificate. Admins email them
                                    // from the certificate "Email to member" action instead.
                                    app(IssueCertificate::class)->execute($this->getOwnerRecord(), $record, auth()->user(), autoEmail: false);
                                    $issued++;
                                } catch (\Throwable $e) {
                                    $skipped++;
                                    // Usually "not completed" / "already issued" — log so the
                                    // bulk "skipped N" count is diagnosable.
                                    Log::warning('Bulk certificate issue skipped member '.$record->id.' (event '.$this->getOwnerRecord()->getKey().'): '.$e->getMessage());
                                }
                            }
                            $this->certificateMap = null; // refresh so new certs show
                            Notification::make()
                                ->title("Issued {$issued}, skipped {$skipped}")
                                ->body($issued > 0 ? 'Use “Email to member” on a certificate to send it.' : null)
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    protected function setPivot($record, array $values): void
    {
        $this->getOwnerRecord()->members()->updateExistingPivot($record->id, $values);
        Notification::make()->title('Attendance updated')->success()->send();
    }

    protected function certificateFor($record): ?Certificate
    {
        if ($this->certificateMap === null) {
            $this->certificateMap = Certificate::where('event_id', $this->getOwnerRecord()->getKey())
                ->get()
                ->keyBy('member_id');
        }

        return $this->certificateMap->get($record->id);
    }
}
