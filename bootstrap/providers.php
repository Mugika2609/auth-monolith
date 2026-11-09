<?php

return [
    App\Providers\AppServiceProvider::class,
    Modules\Auth\Providers\AuthServiceProvider::class,
    Modules\Library\Providers\LibraryServiceProvider::class,
    Modules\Catalog\Providers\CatalogServiceProvider::class,
    Modules\Membership\Providers\MembershipServiceProvider::class,
    Modules\Circulation\Providers\CirculationServiceProvider::class,
];
