<?php

namespace App\Providers\ShortLink;

use App\Services\ShortLink\Interfaces\ShortLinkRepositoryInterface;
use App\Services\ShortLink\Repositories\ShortLinkRepository;
use App\Services\ShortLink\ShortLinkService;
use Illuminate\Support\ServiceProvider;

class ShortLinkServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ShortLinkRepositoryInterface::class, ShortLinkRepository::class);

        $this->app->bind(
            ShortLinkService::class,
            function () {
                return new ShortLinkService(new ShortLinkRepository());
            }
        );
    }
}
