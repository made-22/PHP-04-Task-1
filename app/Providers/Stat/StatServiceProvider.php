<?php

namespace App\Providers\Stat;

use App\Services\Stat\Interfaces\StatRepositoryInterface;
use App\Services\Stat\Repositories\StatRepository;
use App\Services\Stat\StatService;
use Illuminate\Support\ServiceProvider;

class StatServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(StatRepositoryInterface::class, StatRepository::class);

        $this->app->bind(
            StatService::class,
            function () {
                return new StatService(new StatRepository());
            }
        );
    }
}
