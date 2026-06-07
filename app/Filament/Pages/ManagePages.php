<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Support\PageVisibility;
use BackedEnum;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManagePages extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected string $view = 'filament.pages.manage-pages';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEyeSlash;

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Page Visibility';

    protected static ?string $title = 'Page Visibility';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return (bool) (auth()->user()?->hasPermission('settings'));
    }

    public function mount(): void
    {
        $values = [];
        foreach (PageVisibility::all() as $key => $page) {
            $values["page_{$key}_active"] = $page['active'];
        }

        $this->form->fill($values);
    }

    public function form(Schema $schema): Schema
    {
        $toggles = [];
        foreach (PageVisibility::all() as $key => $page) {
            $toggles[] = Toggle::make("page_{$key}_active")
                ->label($page['label_en'])
                ->helperText($page['label_ar']);
        }

        return $schema
            ->components([
                Section::make('Public pages')
                    ->description('Turn a page on or off. When off, it is hidden from the menu and returns "Not Found" if opened directly.')
                    ->schema($toggles),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        foreach ($this->form->getState() as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value ? '1' : '0']);
        }

        Notification::make()->title('Page visibility saved.')->success()->send();
    }
}
