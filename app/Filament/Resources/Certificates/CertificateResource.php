<?php

namespace App\Filament\Resources\Certificates;

use App\Filament\Resources\Certificates\Pages\ListCertificates;
use App\Filament\Resources\Certificates\Pages\ViewCertificate;
use App\Filament\Resources\Certificates\Schemas\CertificateInfolist;
use App\Filament\Resources\Certificates\Tables\CertificatesTable;
use App\Actions\Certificates\SendCertificateEmail;
use App\Models\Certificate;
use App\Services\CertificatePdf;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class CertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static string|\UnitEnum|null $navigationGroup = 'Community';

    protected static ?int $navigationSort = 4;

    public static function downloadAction(): Action
    {
        return Action::make('download')
            ->label('Download')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('gray')
            ->action(fn (Certificate $record) => response()->streamDownload(
                fn () => print(app(CertificatePdf::class)->render($record)),
                'certificate-'.$record->serial.'.pdf',
            ));
    }

    public static function emailAction(): Action
    {
        return Action::make('email')
            ->label(fn (Certificate $record) => $record->emailed_at ? 'Resend email' : 'Email to member')
            ->icon('heroicon-o-paper-airplane')
            ->color('primary')
            ->visible(fn (Certificate $record) => $record->revoked_at === null)
            ->modalHeading('Email certificate to member')
            ->modalSubmitActionLabel('Send')
            ->fillForm(fn (): array => ['locale' => app()->getLocale()])
            ->schema([
                Select::make('locale')
                    ->label('Language')
                    ->options(['en' => 'English', 'ar' => 'العربية'])
                    ->required(),
            ])
            ->action(function (Certificate $record, array $data): void {
                try {
                    app(SendCertificateEmail::class)->execute($record, $data['locale']);

                    Notification::make()
                        ->title('Certificate emailed to '.($record->member?->email ?? 'member'))
                        ->success()
                        ->send();
                } catch (\Throwable $e) {
                    Notification::make()
                        ->title('Could not send certificate')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }

    public static function revokeAction(): Action
    {
        return Action::make('revoke')
            ->label('Revoke')
            ->icon('heroicon-o-x-circle')
            ->color('danger')
            ->requiresConfirmation()
            ->visible(fn (Certificate $record) => $record->revoked_at === null)
            ->action(fn (Certificate $record) => $record->update(['revoked_at' => now()]));
    }

    public static function reinstateAction(): Action
    {
        return Action::make('reinstate')
            ->label('Reinstate')
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->requiresConfirmation()
            ->visible(fn (Certificate $record) => $record->revoked_at !== null)
            ->action(fn (Certificate $record) => $record->update(['revoked_at' => null]));
    }

    public static function infolist(Schema $schema): Schema
    {
        return CertificateInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CertificatesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCertificates::route('/'),
            'view' => ViewCertificate::route('/{record}'),
        ];
    }
}
