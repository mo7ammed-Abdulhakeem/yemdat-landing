<?php

namespace App\Filament\Resources\Members\Pages;

use App\Filament\Resources\Members\MemberResource;
use App\Models\Member;
use App\Support\CsvExport;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Export CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(fn () => CsvExport::download(
                    'members-'.now()->format('Y-m-d').'.csv',
                    ['Full name', 'Email', 'Membership', 'Gender', 'Phone', 'Country', 'University Major', 'Education', 'LinkedIn', 'Bio', 'Email verified at', 'Unsubscribed at', 'Joined'],
                    Member::query()->with('specialtyOption')->orderBy('created_at')->lazy()->map(fn (Member $m) => [
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
                )),
            CreateAction::make(),
        ];
    }
}
