<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;


Route::get(Config::get('laravel-newrelic.monitor.endpoint'), static function () {
    if (! Config::get('laravel-newrelic.enabled') || ! Config::get('laravel-newrelic.monitor.enabled')) {
        App::abort(404);
    }

    return new Response(Config::get('laravel-newrelic.monitor.response'));
});
