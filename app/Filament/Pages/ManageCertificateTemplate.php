<?php

namespace App\Filament\Pages;

use App\Models\CertificateTemplate;
use App\Services\CertificatePdf;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;

class ManageCertificateTemplate extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected string $view = 'filament.pages.manage-certificate-template';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Certificate Template';

    protected static ?string $title = 'Certificate Template';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return (bool) (auth()->user()?->hasPermission('settings'));
    }

    public function mount(): void
    {
        $template = CertificateTemplate::query()->first();

        $this->form->fill([
            'body' => $template->body ?? CertificateTemplate::defaultBody(),
            'background_image' => $template->background_image ?? null,
            'paper' => $template->paper ?? 'A4-L',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Design')
                    ->description('The certificate is rendered from this HTML by mPDF (a subset of HTML/CSS). One design is used for all certificates.')
                    ->schema([
                        Select::make('paper')
                            ->label('Paper size')
                            ->options([
                                'A4-L' => 'A4 — Landscape',
                                'A4' => 'A4 — Portrait',
                                'A5-L' => 'A5 — Landscape',
                                'Letter-L' => 'Letter — Landscape',
                            ])
                            ->default('A4-L')
                            ->native(false),
                        FileUpload::make('background_image')
                            ->label('Background image (optional)')
                            ->image()
                            ->disk('public')
                            ->directory('certificate-backgrounds')
                            ->helperText('Reference it in your HTML/CSS via the {background_url} token.'),
                        Textarea::make('body')
                            ->label('Certificate HTML')
                            ->rows(20)
                            ->extraAttributes(['style' => 'font-family: monospace;'])
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label('Download preview')
                ->icon('heroicon-o-eye')
                ->action(function () {
                    $state = $this->form->getState();
                    $bg = is_string($state['background_image'] ?? null) ? $state['background_image'] : null;

                    return response()->streamDownload(
                        fn () => print(app(CertificatePdf::class)->renderSample($state['body'] ?? null, $state['paper'] ?? 'A4-L', $bg)),
                        'certificate-preview.pdf',
                    );
                }),
        ];
    }

    public function save(): void
    {
        $state = $this->form->getState();

        $template = CertificateTemplate::query()->first() ?? new CertificateTemplate();
        $template->fill([
            'body' => $state['body'],
            'background_image' => $state['background_image'] ?? null,
            'paper' => $state['paper'] ?? 'A4-L',
        ])->save();

        Notification::make()->title('Certificate template saved.')->success()->send();
    }
}
