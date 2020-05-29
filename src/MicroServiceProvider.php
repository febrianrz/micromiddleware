<?php

namespace Febrianrz\Micromidlleware;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;


class MicroServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/micro.php' => config_path('micro.php'),
        ], 'config');
	Validator::extend(
            'google2fa',
            'Febrianrz\\Micromidlleware\\Validators\\GAValidator@validate'
        );
        Validator::replacer('google2fa', function ($message, $attribute, $rule, $parameters) {
            return 'Invalid Authenticator';
        });
    }
    public function register()
    {
        $this->mergeConfigFrom( __DIR__.'/micro.php', 'micro');
    }

}
