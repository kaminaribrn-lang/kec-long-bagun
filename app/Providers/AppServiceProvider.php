<?php

namespace App\Providers;

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
        // Halaman depan: alias pembantu untuk Blade dan pengaturan situs di semua tampilan site.*
        \Illuminate\Foundation\AliasLoader::getInstance()->alias('Situs', \App\Support\Situs::class);
        \Illuminate\Support\Facades\View::composer('site.*', fn ($view) => $view->with('site', \App\Support\Situs::pengaturan()));

        foreach ([\App\Models\Kampung::class, \App\Models\Layanan::class, \App\Models\Destinasi::class, \App\Models\Statistik::class, \App\Models\DokumenPublik::class, \App\Models\Aparatur::class] as $model) {
            \Illuminate\Support\Facades\Gate::policy($model, \App\Policies\KecamatanPolicy::class);
        }

        \BezhanSalleh\LanguageSwitch\LanguageSwitch::configureUsing(function (\BezhanSalleh\LanguageSwitch\LanguageSwitch $switch) {
            $switch
                ->locales(['id', 'en'])
                ->labels([
                    'id' => 'Indonesia',
                    'en' => 'English',
                ])
                ->visible(fn () => false);
        });
    }
}
