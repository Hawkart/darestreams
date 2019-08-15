<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        $this->bootStreamLabsSocialite();
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function bootStreamLabsSocialite()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'streamlabs',
            function ($app) use ($socialite) {
                $config = $app['config']['services.streamlabs'];
                return $socialite->buildProvider(SreamlabsProvider::class, $config);
            }
        );
    }
}
