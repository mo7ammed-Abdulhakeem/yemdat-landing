<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageSettings extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected string $view = 'filament.pages.manage-settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Settings';

    protected static ?string $title = 'Site Settings';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return (bool) (auth()->user()?->hasPermission('settings'));
    }

    public function mount(): void
    {
        $values = Setting::pluck('value', 'key')->toArray();
        $values['notification_bar_enabled'] = (bool) ($values['notification_bar_enabled'] ?? false);

        $this->form->fill($values);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Top Notification Bar')->schema([
                    Toggle::make('notification_bar_enabled')->label('Enable notification bar'),
                    TextInput::make('notification_bar_text')->label('Notification text')->maxLength(255),
                ]),
                Section::make('Contact Information')->columns(2)->schema([
                    TextInput::make('site_email')->label('Public contact email')->email(),
                    TextInput::make('admin_email')->label('Internal admin email (receives form submissions)')->email(),
                ]),
                Section::make('Social Media Links')->columns(2)->schema([
                    TextInput::make('site_facebook')->label('Facebook URL')->url(),
                    TextInput::make('site_twitter')->label('Twitter (X) URL')->url(),
                    TextInput::make('site_instagram')->label('Instagram URL')->url(),
                    TextInput::make('site_linkedin')->label('LinkedIn URL')->url(),
                    TextInput::make('site_whatsapp')->label('WhatsApp URL')->url(),
                ]),
                Section::make('"Be a Trainer" Form')->columns(2)->collapsed()->schema([
                    TextInput::make('trainer_form_title_en')->label('Form title (EN)'),
                    TextInput::make('trainer_form_title_ar')->label('Form title (AR)'),
                    Textarea::make('trainer_form_notes_en')->label('Top description (EN)')->columnSpanFull(),
                    Textarea::make('trainer_form_notes_ar')->label('Top description (AR)')->columnSpanFull(),
                    Textarea::make('trainer_topic_notes_en')->label("Notes above 'Help Topic' (EN)")->columnSpanFull(),
                    Textarea::make('trainer_topic_notes_ar')->label("Notes above 'Help Topic' (AR)")->columnSpanFull(),
                    TextInput::make('trainer_label_name_en')->label('Name label (EN)'),
                    TextInput::make('trainer_label_name_ar')->label('Name label (AR)'),
                    TextInput::make('trainer_label_email_en')->label('Email label (EN)'),
                    TextInput::make('trainer_label_email_ar')->label('Email label (AR)'),
                    TextInput::make('trainer_label_phone_en')->label('Phone label (EN)'),
                    TextInput::make('trainer_label_phone_ar')->label('Phone label (AR)'),
                    TextInput::make('trainer_label_program_type_en')->label('Program type label (EN)'),
                    TextInput::make('trainer_label_program_type_ar')->label('Program type label (AR)'),
                    TextInput::make('trainer_label_duration_en')->label('Duration (hours) label (EN)'),
                    TextInput::make('trainer_label_duration_ar')->label('Duration (hours) label (AR)'),
                    TextInput::make('trainer_label_days_en')->label('Duration (days) label (EN)'),
                    TextInput::make('trainer_label_days_ar')->label('Duration (days) label (AR)'),
                    TextInput::make('trainer_label_agenda_en')->label('Agenda label (EN)'),
                    TextInput::make('trainer_label_agenda_ar')->label('Agenda label (AR)'),
                    Textarea::make('trainer_agreement_text_en')->label('Agreement text (EN)')->columnSpanFull(),
                    Textarea::make('trainer_agreement_text_ar')->label('Agreement text (AR)')->columnSpanFull(),
                ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        foreach ($this->form->getState() as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? '1' : '0';
            }

            Setting::updateOrCreate(['key' => $key], ['value' => (string) ($value ?? '')]);
        }

        Notification::make()->title('Settings saved.')->success()->send();
    }
}
