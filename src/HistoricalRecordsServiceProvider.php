<?php

namespace HistoricalRecords;

use HistoricalRecords\Contracts\HistoryRepository as HistoryRepositoryContract;
use Illuminate\Support\ServiceProvider;

class HistoricalRecordsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(HistoryRepositoryContract::class, HistoryRepository::class);

        $this->mergeConfigFrom(__DIR__.'/../config/historical-records.php', 'historical-records');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPublishing();
        $this->registerCommands();
    }

    /**
     * Register the package's publishable resources.
     */
    protected function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations' => $this->app->databasePath('migrations'),
                __DIR__.'/../config/historical-records.php' => $this->app->configPath('historical-records.php'),
                __DIR__.'/../lang/en' => $this->app->basePath('lang/en'),
            ], 'historical-records');
        }
    }

    /**
     * Register the Passport Artisan commands.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\InstallCommand::class,
                Console\HistoryCleanup::class,
            ]);
        }
    }
}
