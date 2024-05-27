<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('translation.loader', function ($app) {
            return new FileLoader($app['files'], [__DIR__.'/../../lang', $app['path.lang']]);
        });

        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];

            $trans = new Translator($loader, 'en');
            $trans->setFallback($app->getFallbackLocale());

            return $trans;
        });
    }
}
