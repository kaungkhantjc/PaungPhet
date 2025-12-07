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
                'ဂျန်နဝါရီႏ',
                'ဖေꩻဖော်ဝါရီႏ',
                'မတ်',
                'ဧပရယ်',
                'မေ',
                'ဂျွန်',
                'ဂျူလိုင်',
                'ဩဂဲစ်',
                'စက်တဲင်ဘာ',
                'အောက်တိုဘာ',
                'နိုဝဲဉ်ဘာ',
                'ဒီဇဲန်ဘာ'
            ],
            'weekdays' => [
                'တနင်ꩻနွေႏ',
                'တနင်ꩻလာႏ',
                'အင်္ဂါႏ',
                'ဗုဒ္ဓဟူꩻ',
                'ကျာႏသပတေꩻ',
                'သောကျာႏ',
                'စနေႏ'
            ],
        ]);

    }
}
