<?php

namespace App\Constants;

enum Theme: string
{
    case Default = 'default';
    case Aurora = 'aurora';

    public static function values(): array
    {
        return collect(self::cases())->map(fn(Theme $theme) => $theme->value)->toArray();
    }

    public static function default(): Theme
    {
        return self::Default;
    }

}
