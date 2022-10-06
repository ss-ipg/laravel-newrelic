<?php

namespace IgorMatkovic\LaravelNewRelic;

use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Queue;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use IgorMatkovic\LaravelNewRelic\Commands\NewRelicDeploymentCommand;

class LaravelNewRelicServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/laravel-newrelic.php',
            'laravel-newrelic'
        );

        $this->app->scoped(NewRelicTransaction::class, fn () => new NewRelicTransaction());
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/config/laravel-newrelic.php' => App::configPath('laravel-newrelic.php'),
        ]);

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        $this->commands([
            NewRelicDeploymentCommand::class,
        ]);

        if (extension_loaded('newrelic')) {
            $this->setAppName();
        }
    }

    private function setAppName(): void
    {
        app(NewRelicTransaction::class)->setAppName();
    }
}
