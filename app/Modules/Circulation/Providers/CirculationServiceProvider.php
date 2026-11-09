<?php

namespace Modules\Circulation\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Modules\Circulation\Events\BookBorrowed;
use Modules\Circulation\Listeners\LogBookBorrowed;

class CirculationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen(BookBorrowed::class, LogBookBorrowed::class);

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }
}