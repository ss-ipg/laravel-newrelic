<?php

return [
    'enabled'     => env('NEWRELIC_ENABLED', true),
    'app_name'    => env('NEWRELIC_APP_NAME', env('APP_NAME', 'Laravel')),
    'deployments' => [
        'api_key'      => env('NEWRELIC_API_KEY'),
        'app_id'       => env('NEWRELIC_APP_ID'),
        'endpoint'     => env('NEWRELIC_API_ENDPOINT', 'https://api.newrelic.com/v2'),
        'default_user' => env('NEWRELIC_DEFAULT_USER','igor@cod3.me'),
    ],
    'monitor'     => [
        'enabled'  => env('NEWRELIC_MONITOR_ENABLED', true),
        'endpoint' => env('NEWRELIC_MONITOR_ENDPOINT', 'newrelic/ping'),
        'response' => env('NEWRELIC_MONITOR_RESPONSE', 'pong'),
    ],
];
