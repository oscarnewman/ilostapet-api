<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FractalServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('\League\Fractal\Manager', function($app) {
            $fractal = new \League\Fractal\Manager;

            // $serializer = new \League\Fractal\Serializer\JsonApiSerializer(env('API_DOMAIN'));
            $serializer = new \League\Fractal\Serializer\ArraySerializer(env('API_DOMAIN'));

            $fractal->setSerializer($serializer);

            return $fractal;
        });

        $this->app->bind('Dingo\Api\Transformer\Adapter\Fractal', function($app) {
            $fractal = $app->make('\League\Fractal\Manager');

            return new \Dingo\Api\Transformer\Adapter\Fractal($fractal, 'include', ',', true);
        });



    }
}
