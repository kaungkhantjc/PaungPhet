<?php

namespace App\Models;

use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Wedding extends Model
{
    use HasTranslations;

    protected $fillable = [
        'user_id',
        'slug',
        'event_date',
        'address_url',
        'og_image_path',
        'bg_image_path',

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

    public function images(): HasMany
    {
        return $this->hasMany(PreweddingImage::class);
    }

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
        ];
    }

    public function getOgImageUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->og_image_path);
    }

    public function getBgImageUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->bg_image_path);
    }

    public function getContentRendererAttribute(): string
    {
        return RichContentRenderer::make($this->content)->toHtml();
    }

}
