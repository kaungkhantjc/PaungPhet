<?php

namespace App\Constants;

use Illuminate\Support\Collection;

enum SupportedLocale: string
{
    case EN = 'en';
    case MY = 'my';
    case MY_PAO = 'my_PAO';
    case MY_SHAN = 'my_SHN';

    public static function values(): array
    {
        return collect(self::cases())->map(fn(SupportedLocale $lan) => $lan->value)->toArray();
    }

    public function flag(): string
    {
        return match ($this) {
            self::MY => 'mm',
            self::MY_PAO => 'mm-pao',
            self::MY_SHAN => 'mm-shn',
            default => 'us',
        };
    }

    public function label(): array|string|null
    {
        return __('supported_locales.' . $this->value);
    }

    public static function all(): Collection
    {
        return collect(SupportedLocale::cases())->map(fn(SupportedLocale $lan) => [
            'code' => $lan->value,
            'name' => $lan->label(),
            'flag' => $lan->flag(),
        ]);
    }

}
