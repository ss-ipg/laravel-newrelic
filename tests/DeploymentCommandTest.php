<?php

namespace SecureSpace\LaravelNewRelic\Tests;

use SecureSpace\LaravelNewRelic\Exceptions\MissingNewrelicApiKeyException;
use SecureSpace\LaravelNewRelic\Exceptions\MissingNewrelicAppIdException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class DeploymentCommandTest extends TestCase
{
    /** @test */
    public function it_throws_an_error_on_missing_api_key(): void
    {
        $this->expectException(MissingNewrelicApiKeyException::class);
        Artisan::call('newrelic:deploy', ['description' => 'Some Description']);
    }

    /** @test */
    public function it_throws_an_error_on_missing_app_id(): void
    {
        Config::set('laravel-newrelic.deployments.api_key', 'RANDOM_API_KEY');

        $this->expectException(MissingNewrelicAppIdException::class);
        Artisan::call('newrelic:deploy', ['description' => 'Some Description']);
    }

    protected function setDefaultValues(): void
    {
        Config::set('laravel-newrelic.deployments.api_key', 'RANDOM_API_KEY');
        Config::set('laravel-newrelic.deployments.app_id', '1234567');
    }

    /** @test */
    public function it_builds_correct_http_url(): void
    {
        $this->setDefaultValues();

        Http::fake();

        $this->artisan('newrelic:deploy', [
            'description' => 'Some Description',
            '--changelog' => 'Some Changelog',
            '--revision'  => 'Some Revision',
            '--user'      => 'some@user.com',
        ]);

        Http::assertSent(static function (Request $request) {
            return $request->hasHeader('Api-Key', 'RANDOM_API_KEY') &&
                $request->url() === 'https://api.newrelic.com/v2/applications/1234567/deployments.json' &&
                Arr::get($request, 'deployment.description') === 'Some Description' &&
                Arr::get($request, 'deployment.changelog') === 'Some Changelog' &&
                Arr::get($request, 'deployment.revision') === 'Some Revision' &&
                Arr::get($request, 'deployment.user') === 'some@user.com';
        });
    }
}