<?php

namespace App\Filament\Resources\Members\Pages;

use App\Actions\Trainers\PromoteMemberToTrainer;
use App\Actions\Trainers\RevokeTrainer;
use App\Actions\Trainers\SendTrainerInvite;
use App\Filament\Concerns\HasRecordNavigation;
use App\Filament\Resources\Members\MemberResource;
use App\Models\Member;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewMember extends ViewRecord
{
    use HasRecordNavigation;

    protected static string $resource = MemberResource::class;

    protected function navPage(): string
    {
        return 'view';
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->previousRecordAction(),
            $this->nextRecordAction(),
            self::makeTrainerAction(),
            self::resendTrainerInviteAction(),
            self::revokeTrainerAction(),
            EditAction::make(),
            DeleteAction::make(),
        ];
    }

    public static function makeTrainerAction(): Action
    {
        return Action::make('makeTrainer')
            ->label('Make trainer')
            ->icon('heroicon-o-academic-cap')
            ->color('success')
            ->requiresConfirmation()
            ->modalDescription('Creates a trainer account for this member and emails them a link to set their password. They can then sign in to the trainer dashboard.')
            ->visible(fn (Member $record) => ! $record->isTrainer())
            ->action(function (Member $record) {
                try {
                    app(PromoteMemberToTrainer::class)->execute($record);
                    Notification::make()->title('Trainer invite sent to '.$record->email)->success()->send();
                } catch (\Throwable $e) {
                    Notification::make()->title('Could not promote to trainer')->body($e->getMessage())->danger()->send();
                }
            });
    }

    public static function resendTrainerInviteAction(): Action
    {
        return Action::make('resendTrainerInvite')
            ->label('Resend trainer invite')
            ->icon('heroicon-o-envelope')
            ->color('gray')
            ->requiresConfirmation()
            ->modalDescription('Sends a fresh "set your password" link to the trainer. Use this if the original invite was not received or its link expired.')
            ->visible(fn (Member $record) => $record->isTrainer())
            ->action(function (Member $record) {
                try {
                    app(SendTrainerInvite::class)->execute($record->user, $record->full_name);
                    Notification::make()->title('Invite re-sent to '.$record->email)->success()->send();
                } catch (\Throwable $e) {
                    Notification::make()->title('Could not send invite')->body($e->getMessage())->danger()->send();
                }
            });
    }

    public static function revokeTrainerAction(): Action
    {
        return Action::make('revokeTrainer')
            ->label('Revoke trainer')
            ->icon('heroicon-o-no-symbol')
            ->color('danger')
            ->requiresConfirmation()
            ->modalDescription('Removes this member\'s trainer account and unassigns them from any events. Their member profile and certificates are kept.')
            ->visible(fn (Member $record) => $record->isTrainer())
            ->action(function (Member $record) {
                app(RevokeTrainer::class)->execute($record);
                Notification::make()->title('Trainer access revoked')->success()->send();
            });
    }
}
