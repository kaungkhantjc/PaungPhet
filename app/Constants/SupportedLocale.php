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
