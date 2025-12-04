<?php

namespace App\Filament\Widgets;

use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GuestStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    /**
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        $wedding = auth()->user()->wedding;

        // If the user hasn't set up a wedding yet, display zeros.
        if (!$wedding) {
            return [
                Stat::make('Total Guests', 0)
                    ->description('Setup your wedding first')
                    ->icon(Heroicon::OutlinedUserGroup),
                Stat::make('Guests Seen', 0)
                    ->description('Invitations opened')
                    ->icon(Heroicon::OutlinedEye),
                Stat::make('Guests Pending', 0)
                    ->description('Invitations not yet opened')
                    ->icon(Heroicon::OutlinedEnvelope),
            ];
        }

        $guestQuery = $wedding->guests();
        $totalGuests = $guestQuery->count();
        $guestsSeen = $guestQuery->where('status', 'seen')->count();
        $guestsPending = $totalGuests - $guestsSeen;

        $seenPercentage = $totalGuests > 0 ? round(($guestsSeen / $totalGuests) * 100) : 0;
        $seenColor = $seenPercentage >= 75 ? Color::Green : ($seenPercentage >= 50 ? Color::Orange : Color::Red);

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
