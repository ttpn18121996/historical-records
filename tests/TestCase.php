<?php

namespace HistoricalRecords\Tests;

use App\Providers\AppServiceProvider;
use hisorange\BrowserDetect\ServiceProvider as BrowserDetectServiceProvider;
use HistoricalRecords\HistoricalRecordsServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;

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
        $app['config']->set('database.default', 'testing');
    }

    /**
     * Register the service.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        $app->singleton('translation.loader', function ($app) {
            return new FileLoader($app['files'], [__DIR__.'/../../lang', $app['path.lang']]);
        });

        $app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];

            $trans = new Translator($loader, 'en');
            $trans->setFallback($app->getFallbackLocale());

            return $trans;
        });

        return [
            AppServiceProvider::class,
            BrowserDetectServiceProvider::class,
            HistoricalRecordsServiceProvider::class,
        ];
    }
}
