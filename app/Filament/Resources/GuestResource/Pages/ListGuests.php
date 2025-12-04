<?php

namespace App\Filament\Resources\GuestResource\Pages;

use App\Filament\Resources\GuestResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListGuests extends ListRecords
{
    protected static string $resource = GuestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->mutateDataUsing(function (array $data) {
                    $data['status'] = 'pending';
                    $data['wedding_id'] = auth()->user()->wedding->id;
                    return $data;
                }),
        ];
    }

    public function __invoke()
    {
        if (auth()->user()->wedding === null) {
            Notification::make()
                ->title(__('filament/admin/guest_resource.wedding_details_required'))
                ->warning()
                ->send();
            return redirect()->route('filament.admin.pages.my-wedding');
        }
        return parent::__invoke();
    }

}
