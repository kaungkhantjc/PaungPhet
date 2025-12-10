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

namespace App\Observers;

use App\Models\Wedding;
use Illuminate\Support\Facades\Storage;

class WeddingObserver
{
    public function updated(Wedding $wedding): void
    {
        $disk = Storage::disk('public');

        // Handle OG Image Replacement
        if ($wedding->wasChanged('og_image_path')) {
            $oldOgImage = $wedding->getOriginal('og_image_path');

            if ($oldOgImage && $disk->exists($oldOgImage)) {
                $disk->delete($oldOgImage);
            }
        }

        // Handle Background Image Replacement
        if ($wedding->wasChanged('bg_image_path')) {
            $oldBgImage = $wedding->getOriginal('bg_image_path');

            if ($oldBgImage && $disk->exists($oldBgImage)) {
                $disk->delete($oldBgImage);
            }
        }
    }


    public function deleted(Wedding $wedding): void
    {
        $disk = Storage::disk('public');

        if ($wedding->og_image_path && $disk->exists($wedding->og_image_path)) {
            $disk->delete($wedding->og_image_path);
        }

        if ($wedding->bg_image_path && $disk->exists($wedding->bg_image_path)) {
            $disk->delete($wedding->bg_image_path);
        }
    }
}
