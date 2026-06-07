<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/**
 * Dedicated, locked-down panel for trainers (staff Users with role=trainer).
 * Trainers manage only the events they are assigned to teach and those events'
 * certificates. The main /admin panel is untouched; canAccessPanel() on the User
 * model keeps trainers out of /admin and admins out of /trainer.
 */
class TrainerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('trainer')
            ->path('trainer')
            ->login()
            // Lets a newly-invited trainer set their password via the emailed link.
            ->passwordReset()
            // Built-in "Edit profile" page (name / email / password) in the user menu.
            ->profile()
            ->brandName('Yemdat Trainer')
            ->colors([
                'primary' => Color::hex('#593E2D'),
                'gray' => Color::Stone,
            ])
            ->sidebarCollapsibleOnDesktop()
            // Language / direction (EN ⇄ AR / LTR ⇄ RTL) toggle in the top bar.
            ->renderHook(
                PanelsRenderHook::TOPBAR_END,
                fn (): string => view('filament.lang-switcher')->render(),
            )
            ->discoverResources(in: app_path('Filament/Trainer/Resources'), for: 'App\Filament\Trainer\Resources')
            ->discoverPages(in: app_path('Filament/Trainer/Pages'), for: 'App\Filament\Trainer\Pages')
            ->discoverWidgets(in: app_path('Filament/Trainer/Widgets'), for: 'App\Filament\Trainer\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \App\Http\Middleware\SetLocale::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
