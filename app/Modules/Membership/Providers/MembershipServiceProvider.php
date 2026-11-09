<?php

namespace Modules\Membership\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Membership\Services\MemberService;
use Modules\Shared\Contracts\MemberContract;

class MembershipServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MemberContract::class, MemberService::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }
}