<?php

namespace SecureSpace\LaravelNewRelic\Tests;

use Illuminate\Support\Facades\Config;

class SyntheticsMonitorPingTest extends TestCase
{
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