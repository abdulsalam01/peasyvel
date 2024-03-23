<?php

namespace App\Providers;

use App\Events\DailyRecordOnChange;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

use App\Listeners\AgeProcessorListener;

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
            DailyRecordOnChange::class,
            AgeProcessorListener::class,
        );
    }
}
