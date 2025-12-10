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

namespace App\Http\Controllers;

use App\Constants\SupportedLocale;
use App\Constants\Theme;
use App\Models\Guest;
use App\Models\Wedding;
use Illuminate\Http\Request;

class GuestController extends Controller
{

    public function show(Request $request, string $locale, string $weddingSlug)
    {
        return $this->invite($request, $locale, $weddingSlug, null);
    }

    public function invite(Request $request, string $locale, string $weddingSlug, ?string $guestSlug)
    {
        $weddingQuery = Wedding::where('slug', $weddingSlug)
            ->with('images:wedding_id,name,path');

        // Load guest if slug is provided
        if ($guestSlug) {
            $weddingQuery->with('guests', fn($query) => $query->where('slug', $guestSlug)->select('id', 'wedding_id', 'name', 'slug', 'status', 'is_notable', 'note'));
        }

        $wedding = $weddingQuery->firstOrFail();

        // Ensure the wedding slug matches if slug provided
        if ($guestSlug && $wedding->guests->isEmpty()) {
            abort(404);
        }

        $guest = $guestSlug ? $wedding->guests->first() : null;

        // Update guest status if the slug provided and the status is pending
        if ($guestSlug && $guest->status === 'pending') {
            $guest->status = 'seen';
            $guest->save();
        }

        $theme = $request->query('theme', Theme::default()->value);
        if (!in_array($theme, Theme::values())) {
            $theme = Theme::default()->value;
        }

        return view("themes.$theme", [
            'wedding' => $wedding,
            'guest' => $guest,
            'locale' => $locale,
            'supportedLocales' => SupportedLocale::all(),
            'theme' => $theme,
        ]);
    }

    public function submitNote(Request $request, string $locale, string $weddingSlug, string $guestSlug)
    {
        $guest = Guest::where('slug', $guestSlug)
            ->with('wedding')
            ->firstOrFail();

        // Ensure the wedding slug matches
        if ($guest->wedding->slug !== $weddingSlug) {
            abort(404);
        }

        // Ensure the guest is notable
        if (!$guest->is_notable) {
            abort(403);
        }

        $note = $request->input('note', '');
        $guest->note = $note;
        $guest->save();

        $theme = $request->query('theme', Theme::default()->value);
        if (!in_array($theme, Theme::values())) {
            $theme = Theme::default()->value;
        }

        return redirect()->route('guests.invite', ['locale' => $locale, 'weddingSlug' => $weddingSlug, 'guestSlug' => $guestSlug, 'theme' => $theme])
            ->with('success', __('theme/default.note_sent'));
    }


}
