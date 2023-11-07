<?php

namespace Hani221b\Grace\Providers;

use Hani221b\Grace\Commands\InstallGrace;
use Hani221b\Grace\Helpers\ResourceRegistrar;
use Illuminate\Support\ServiceProvider;

class GraceServiceProvider extends ServiceProvider
{
    /**
     * Defining Grace package commands
     */
    protected $commands = [
        InstallGrace::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViewsFrom(__DIR__ . '/../Views', 'Grace');
        //adding new methods for a resoureful route
        $registrar = new ResourceRegistrar($this->app['router']);
        $this->app->bind('Illuminate\Routing\ResourceRegistrar', function () use ($registrar) {
            return $registrar;
        });

        //resgister commands
        $this->commands($this->commands);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('grace.status') === 'enabled') {
            include __DIR__ . '/../Routes/routes.php';
        }
        /**
         * publish package stuff
         */
        $this->publishes([
            //config
            __DIR__ . '/../Config/grace.php' => config_path('grace.php'),
            //migrations
            __DIR__ . '/../Database/Migrations' => base_path('database/migrations'),
            //views
            __DIR__ . '/../Views/Grace' => base_path('resources/views\grace'),
            //assets
            __DIR__ . '/../public/assets' => base_path('public/grace/assets'),
            //routes
            __DIR__ . '/../Routes/grace.php' => base_path('routes/grace.php'),
            //seeders
            __DIR__ . '/../Database/Seeders/LanguageSeeder.php' => base_path('database/seeders/LanguageSeeder.php'),
        ], 'grace');

    }
}
