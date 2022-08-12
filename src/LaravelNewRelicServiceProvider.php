<?php

namespace IgorMatkovic\LaravelNewRelic;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use IgorMatkovic\LaravelNewRelic\Commands\NewRelicDeploymentCommand;

class LaravelNewRelicServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/newrelic.php',
            'laravel-newrelic'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/config/newrelic.php' => App::configPath('newrelic.php'),
        ]);

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        $this->commands([
            NewRelicDeploymentCommand::class,
        ]);

        $this->setAppName();
    }

    private function setAppName(): void
    {
        if (extension_loaded('newrelic')) {
            newrelic_set_appname(Config::get('laravel-newrelic.app_name'));
        }
    }
}
