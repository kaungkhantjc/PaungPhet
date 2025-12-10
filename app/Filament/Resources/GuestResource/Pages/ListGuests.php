<?php
/*
 * Copyright (c) 2025 Kaung Khant Kyaw and Khun Htetz Naing.
 *
 * This file is part of the PaungPhet app.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

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
