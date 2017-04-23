<?php

namespace Mrfoh\Mulla;

use Illuminate\Support\ServiceProvider;

class MullaServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        //publish config
        $this->publishes([
          __DIR__.'/../config/mulla.php' => config_path('mulla.php'),
        ], 'config');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
