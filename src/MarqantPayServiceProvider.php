<?php

namespace Marqant\MarqantPay;

use Illuminate\Support\ServiceProvider;
use Marqant\MarqantPay\Commands\MigrationsForBillable;

class MarqantPayServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->setupConfig();

        $this->setupFacades();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupMigrations();

        $this->setupCommands();
    }

    /**
     * Setup configuration in register method.
     *
     * @return void
     */
    private function setupConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/marqant-pay.php', 'marqant-pay');
    }

    /**
     * Setup migrations in boot method.
     *
     * @return void
     */
    private function setupMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * Setup commands in boot method.
     *
     * @return void
     */
    private function setupCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MigrationsForBillable::class,
            ]);
        }
    }

    /**
     * Method to setup facades of this package.
     *
     * @return void
     */
    private function setupFacades()
    {
    }
}