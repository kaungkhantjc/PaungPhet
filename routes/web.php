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

use App\Constants\SupportedLocale;
use App\Http\Controllers\GuestController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::get('/{locale?}', function (?string $locale = null) {
    return view('welcome', [
        'locale' => $locale ?? SupportedLocale::MY->value,
        'supportedLocales' => SupportedLocale::all(),
    ]);
})->whereIn('locale', SupportedLocale::values())
    ->middleware(SetLocale::class)->name('welcome');

Route::prefix('{locale}')
    ->whereIn('locale', SupportedLocale::values())
    ->middleware(SetLocale::class)
    ->group(function () {
        Route::get('/{weddingSlug}', [GuestController::class, 'show'])->name('guests.show');
        Route::get('/{weddingSlug}/invite/{guestSlug}', [GuestController::class, 'invite'])->name('guests.invite');
        Route::post('/{weddingSlug}/invite/{guestSlug}', [GuestController::class, 'submitNote'])->name('guests.submitNote');
    });
