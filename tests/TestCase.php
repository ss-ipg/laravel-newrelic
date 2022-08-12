<?php

namespace IgorMatkovic\LaravelNewRelic\Tests;

use IgorMatkovic\LaravelNewRelic\LaravelNewRelicServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelNewRelicServiceProvider::class,
        ];
    }
}