<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Wedding;
use Illuminate\Http\Request;

class GuestController extends Controller
{

    public function show(string $locale, string $weddingSlug, string $guestSlug)
    {
        $wedding = Wedding::where('slug', $weddingSlug)
            ->with('images:wedding_id,name,path')
            ->with('guests', fn($query) => $query->where('slug', $guestSlug)->select('wedding_id', 'name', 'slug', 'status', 'is_notable', 'note'))
            ->firstOrFail();

        // Ensure the wedding slug matches
        if ($wedding->guests->isEmpty()) {
            abort(404);
        }

        $guest = $wedding->guests->first();

        // Update guest status if pending
        if ($guest->status === 'pending') {
            $guest->status = 'seen';
            $guest->save();
        }

        return view("themes.default", [
            'wedding' => $guest->wedding,
            'guest' => $guest,
            'locale' => $locale,
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
