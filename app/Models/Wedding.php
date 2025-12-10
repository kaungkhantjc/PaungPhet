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

namespace App\Models;

use App\Observers\WeddingObserver;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

#[ObservedBy(WeddingObserver::class)]
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

    public function getBgImageUrlAttribute(): ?string
    {
        return $this->bg_image_path ? Storage::disk('public')->url($this->bg_image_path) : null;
    }

    public function getContentRendererAttribute(): string
    {
        return RichContentRenderer::make($this->content)->toHtml();
    }

}
