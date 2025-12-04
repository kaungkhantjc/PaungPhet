<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wedding invitation</title>
</head>
<body>
<div>
    <h4>{{ $wedding->partner_one }}</h4> & <h4>{{ $wedding->partner_two }}</h4>

    @if($guest->is_notable)
        @if(\Illuminate\Support\Str::of($guest->note)->trim()->isNotEmpty())
            <blockquote>Note has been sent successfully.</blockquote>
        @else
            <form method="POST"
                  action="{{ route('guests.submitNote', ['locale' => $locale, 'weddingSlug' => $wedding->slug, 'guestSlug' => $guest->slug]) }}">
                @csrf
                <label for="note">Note</label>
                <textarea id="note" name="note" required></textarea>
                <button type="submit">Submit</button>
            </form>
        @endif

    @endif

</div>
</body>
</html>
