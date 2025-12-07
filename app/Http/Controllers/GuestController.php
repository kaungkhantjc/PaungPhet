<?php

namespace App\Http\Controllers;

use App\Constants\SupportedLocale;
use App\Models\Guest;
use App\Models\Wedding;
use Illuminate\Http\Request;

class GuestController extends Controller
{

    public function show(string $locale, string $weddingSlug)
    {
        return $this->invite($locale, $weddingSlug, null);
    }

    public function invite(string $locale, string $weddingSlug, ?string $guestSlug)
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

        return view("themes.default", [
            'wedding' => $wedding,
            'guest' => $guest,
            'locale' => $locale,
            'supportedLocales' => SupportedLocale::all()
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

        return redirect()->route('guests.show', ['locale' => $locale, 'weddingSlug' => $weddingSlug, 'guestSlug' => $guestSlug])
            ->with('success', __('theme.default.note_sent'));
    }


}
