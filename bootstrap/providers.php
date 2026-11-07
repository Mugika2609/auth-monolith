<?php

use App\Providers\AppServiceProvider;
use Modules\Auth\Providers\AuthServiceProvider;
use Modules\Library\Providers\LibraryServiceProvider;

return [
    AppServiceProvider::class,
    AuthServiceProvider::class,
    LibraryServiceProvider::class,
];