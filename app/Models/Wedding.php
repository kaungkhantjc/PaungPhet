<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Wedding extends Model
{
    use HasTranslations;

    protected $fillable = [
        'user_id',
        'slug',
        'event_date',
        'address_url',

        'partner_one',
        'partner_two',
        'content',
        'event_time',
        'address'
    ];

    public array $translatable = [
        'partner_one',
        'partner_two',
        'content',
        'event_time',
        'address'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
        ];
    }
}
