<?php

namespace Febrianrz\Micromidlleware;

use Illuminate\Support\ServiceProvider;


class MicroServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/micro.php' => config_path('micro.php'),
        ], 'config');
    }
    public function register()
    {
        $this->mergeConfigFrom( __DIR__.'/micro.php', 'micro');
    }

}
