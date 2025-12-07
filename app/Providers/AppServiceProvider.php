<?php

namespace App\Providers;

use Carbon\Translator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Register Burmese (Pa-O) month and weekday names
        $translator = Translator::get('my_BLK');
        $translator->setTranslations([
            'months' => [
                'ဇန်နဝါရီ PaOh', 'ဖေဖော်ဝါရီ PaOh', 'မတ် PaOh', 'ဧပြီ PaOh', 'မေ PaOh', 'ဇွန် PaOh',
                'ဇူလိုင် PaOh', 'သြဂုတ် PaOh', 'စက်တင်ဘာ PaOh', 'အောက်တိုဘာ PaOh', 'နိုဝင်ဘာ PaOh', 'ဒီဇင်ဘာ PaOh'
            ],
            'weekdays' => [
                'တနင်္ဂနွေ PaOh', 'တနင်္လာ PaOh', 'အင်္ဂါ PaOh', 'ဗုဒ္ဓဟူး PaOh', 'ကြာသပတေး PaOh', 'သောကြာ PaOh', 'စနေ PaOh'
            ],
        ]);

    }
}
