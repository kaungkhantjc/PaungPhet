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

namespace App\Filament\Widgets;

use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GuestStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    protected static bool $isLazy = false;

    /**
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        $wedding = auth()->user()->wedding;

        if ($wedding) {
            $guestQuery = $wedding->guests();
            $totalGuests = $guestQuery->count();
            $guestsSeen = $guestQuery->where('status', 'seen')->count();
            $guestsPending = $totalGuests - $guestsSeen;

            $seenPercentage = $totalGuests > 0 ? round(($guestsSeen / $totalGuests) * 100) : 0;
            $seenColor = $seenPercentage >= 75 ? Color::Green : ($seenPercentage >= 50 ? Color::Orange : Color::Red);
        } else {
            $totalGuests = 0;
            $guestsSeen = 0;
            $guestsPending = 0;
            $seenPercentage = 0;
            $seenColor = Color::Gray;
        }


        // --- Return Stats ---
        return [
            Stat::make(__('filament/admin/guest_stats.total_guests'), $totalGuests)
                ->description(__('filament/admin/guest_stats.total_guests_description'))
                ->icon(Heroicon::OutlinedEnvelope),

            Stat::make(__('filament/admin/guest_stats.guests_seen'), $guestsSeen)
                ->icon(Heroicon::OutlinedEnvelopeOpen)
                ->description(__('filament/admin/guest_stats.n_of_total_guests', ['count' => $seenPercentage]))
                ->descriptionIcon(Heroicon::OutlinedEye)
                ->color($seenColor),

            Stat::make(__('filament/admin/guest_stats.guests_pending'), $guestsPending)
                ->icon(Heroicon::OutlinedEnvelope)
                ->description(__('filament/admin/guest_stats.still_waiting_to_open'))
                ->descriptionIcon(Heroicon::OutlinedClock)
                ->color(Color::Gray),
        ];
    }
}
