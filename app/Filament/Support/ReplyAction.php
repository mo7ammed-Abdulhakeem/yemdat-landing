<?php

namespace App\Filament\Support;

use App\Actions\SendAdminReply;
use App\Models\Setting;
use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

/**
 * Reusable "Reply" action for repliable records (Contact, TrainerRequest).
 * Opens a compose modal (from name/email, subject, rich-text body), sends the
 * reply via SendAdminReply, and records it in the reply history.
 *
 * Usable as both a table row action and a page header action.
 */
class ReplyAction
{
    /**
     * @param  Closure(\Illuminate\Database\Eloquent\Model): string  $defaultSubject
     */
    public static function make(Closure $defaultSubject): Action
    {
        return Action::make('reply')
            ->label('Reply')
            ->icon('heroicon-o-arrow-uturn-left')
            ->color('primary')
            ->modalHeading('Send a reply')
            ->modalSubmitActionLabel('Send reply')
            ->fillForm(fn ($record): array => [
                'from_name' => Setting::get('reply_from_name', config('mail.from.name')),
                'from_email' => Setting::get('reply_from_email', config('mail.from.address')),
                'subject' => $defaultSubject($record),
            ])
            ->schema([
                TextInput::make('from_name')
                    ->label('From name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('from_email')
                    ->label('From email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('subject')
                    ->required()
                    ->maxLength(255),
                RichEditor::make('body')
                    ->label('Message')
                    ->required()
                    ->columnSpanFull(),
            ])
            ->action(function (array $data, $record): void {
                app(SendAdminReply::class)->execute($record, $data, auth()->user());

                Notification::make()
                    ->title('Reply sent to '.$record->email)
                    ->success()
                    ->send();
            });
    }
}
