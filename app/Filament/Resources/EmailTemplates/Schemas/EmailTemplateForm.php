<?php

namespace App\Filament\Resources\EmailTemplates\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class EmailTemplateForm
{
    /**
     * The Mail classes that render via this template system, mapped to the
     * placeholders each one provides. A template is wired to a Mail class by
     * matching `mailable_class` to the class basename — so adding a row here
     * only does something if a Mail class of the same name sends it.
     *
     * @return array<string, array<int, string>>
     */
    public static function mailables(): array
    {
        return [
            'SignupOtpEmail' => ['name', 'otp'],
            'WelcomeEmail' => ['name'],
            'PasswordResetOtpEmail' => ['name', 'otp'],
            'EventConfirmationEmail' => ['name', 'event_title', 'start_date', 'location', 'join_url_text'],
            'EventReminderEmail' => ['name', 'event_title', 'start_date', 'location', 'join_url_text'],
            'ContactUsAutoReplyEmail' => ['name'],
            'AccountDeletionOtpEmail' => ['name', 'otp'],
            'TrainerAutoReplyEmail' => ['name', 'email', 'phone_number', 'help_topic'],
            'CertificateIssuedEmail' => ['name', 'event_title', 'serial', 'issued_date', 'verify_url'],
        ];
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('mailable_class')
                    ->label('Email type')
                    ->options(fn () => collect(static::mailables())->keys()->mapWithKeys(fn ($c) => [$c => $c]))
                    ->searchable()
                    ->required()
                    ->live()
                    // The mailable_class is the template's key — never edit it on an existing row.
                    ->disabled(fn (string $operation): bool => $operation === 'edit')
                    ->helperText('Which system email this template controls.'),
                Placeholder::make('placeholders_help')
                    ->label('Available placeholders')
                    ->content(fn (Get $get): HtmlString => static::placeholderHint($get('mailable_class')))
                    ->columnSpanFull(),
                TextInput::make('from_email')
                    ->email()
                    ->helperText('Optional. Leave blank to use the default sender.')
                    ->default(null),
                Toggle::make('is_active')
                    ->helperText('Inactive templates are not sent.')
                    ->default(true)
                    ->required(),
                TextInput::make('subject_en')
                    ->label('Subject (EN)')
                    ->required(),
                TextInput::make('subject_ar')
                    ->label('Subject (AR)')
                    ->required(),
                Textarea::make('body_en')
                    ->label('Body (EN) — HTML allowed')
                    ->rows(14)
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('body_ar')
                    ->label('Body (AR) — HTML allowed')
                    ->rows(14)
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('banner_image')
                    ->image()
                    ->disk('public')
                    ->directory('email-banners'),
            ]);
    }

    protected static function placeholderHint(?string $mailable): HtmlString
    {
        $tokens = static::mailables()[$mailable] ?? [];

        if (empty($tokens)) {
            return new HtmlString('<span style="color:#9ca3af">Select an email type to see the placeholders you can use in the subject and body.</span>');
        }

        $chips = collect($tokens)
            ->map(fn ($t) => '<code>{'.e($t).'}</code>')
            ->implode(' &nbsp; ');

        return new HtmlString('Use these in the subject/body: '.$chips);
    }
}
