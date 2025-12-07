<?php

use App\Constants\SupportedLocale;
use App\Http\Controllers\GuestController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('{locale}')
    ->middleware(SetLocale::class)
    ->group(function () {
        Route::get('/{weddingSlug}', [GuestController::class, 'show'])->name('guests.show');
        Route::get('/{weddingSlug}/invite/{guestSlug}', [GuestController::class, 'invite'])->name('guests.invite');
        Route::post('/{weddingSlug}/invite/{guestSlug}', [GuestController::class, 'submitNote'])->name('guests.submitNote');
    })->whereIn('locale', SupportedLocale::values());
