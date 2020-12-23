<?php

namespace LaraAreaSeo\Providers;

use LaraAreaSupport\LaraAreaServiceProvider;

class ServiceProvider extends LaraAreaServiceProvider
{
    /**
     *
     */
    public function boot()
    {
        $this->mergeConfig(__DIR__);
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }
}

