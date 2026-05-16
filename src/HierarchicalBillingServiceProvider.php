<?php

namespace MahmoudElBoshy\HierarchicalBilling;

use Illuminate\Support\ServiceProvider;

class HierarchicalBillingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/Database/migrations/' => database_path('migrations'),
            ], 'hierarchical-billing-migrations');

            $this->publishes([
                __DIR__ . '/Config/hierarchical-billing.php' => config_path('hierarchical-billing.php'),
            ], 'hierarchical-billing-config');
        }

        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/Config/hierarchical-billing.php',
            'hierarchical-billing'
        );

        $this->app->singleton('hierarchical-billing', function ($app) {
            return new HierarchicalBillingManager($app);
        });
    }
}
