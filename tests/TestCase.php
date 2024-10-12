<?php

namespace HistoricalRecords\Tests;

use hisorange\BrowserDetect\ServiceProvider as BrowserDetectServiceProvider;
use HistoricalRecords\HistoricalRecordsServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabaseState;

use function Orchestra\Testbench\artisan;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        if (class_exists(RefreshDatabaseState::class)) {
            RefreshDatabaseState::$migrated = false;
        }

        parent::setUp();
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        artisan($this, 'migrate');
        $this->loadMigrationsFrom(__DIR__.'/../workbench/database/migrations');
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', __DIR__.'/../workbench/database/database.sqlite');
    }

    /**
     * Register the service.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            BrowserDetectServiceProvider::class,
            HistoricalRecordsServiceProvider::class,
        ];
    }
}
