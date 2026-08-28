<?php

namespace App\Providers;

use App\Events\SuratDiterbitkanEvent;
use App\Listeners\SyncToBukuAgendaListener;
use App\Listeners\SyncToBukuEkspedisiListener;
use Illuminate\Support\Facades\Event;
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
        Event::listen(
            SuratDiterbitkanEvent::class,
            SyncToBukuEkspedisiListener::class,
        );

        Event::listen(
            SuratDiterbitkanEvent::class,
            SyncToBukuAgendaListener::class,
        );
    }
}
