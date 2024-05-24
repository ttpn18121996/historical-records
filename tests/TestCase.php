<?php 

namespace HistoricalRecords\Tests;

use HistoricalRecords\HistoricalRecordsServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabaseState;

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
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        $app['config']->set('database.default', 'testing');
    }

    /**
     * Register the service.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [HistoricalRecordsServiceProvider::class];
    }
}
