<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guest extends Model
{
    protected $fillable = [
        'wedding_id',
        'name',
        'slug',
        'status',
        'is_notable',
        'note',
    ];

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    protected function casts(): array
    {
        return [
            'is_notable' => 'boolean',
        ];
    }
}
