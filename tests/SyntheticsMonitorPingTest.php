<?php

namespace IgorMatkovic\LaravelNewRelic\Tests;

use IgorMatkovic\LaravelNewRelic\Exceptions\MissingNewrelicApiKeyException;
use IgorMatkovic\LaravelNewRelic\Exceptions\MissingNewrelicAppIdException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class SyntheticsMonitorPingTest extends TestCase
{
    /** @test */
    public function monitor_endpoint_is_disabled_when_the_whole_integration_is_off(): void
    {
        Config::set('laravel-newrelic.enabled', false);

        $this->get('/newrelic/ping')
             ->assertStatus(404);
    }

    /** @test */
    public function monitor_endpoint_can_be_disabled_separately_from_the_integration(): void
    {
        Config::set('laravel-newrelic.monitor.enabled', false);

        $this->get('/newrelic/ping')
             ->assertStatus(404);
    }

    /** @test */
    public function it_returns_specified_values_from_config(): void
    {
        $this->get('newrelic/ping')
             ->assertSee('pong');
    }
}