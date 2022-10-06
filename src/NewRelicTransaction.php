<?php

namespace IgorMatkovic\LaravelNewRelic;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class NewRelicTransaction
{
    public function __construct() {}

    public function setAppName(): void
    {
        newrelic_set_appname(Config::get('laravel-newrelic.app_name'));
    }

    public function start(string $name, array $params = []): self
    {
        return $this->startTransaction()
                    ->setTransactionName($name)
                    ->setTransactionParams($params);
    }

    public function markAsBackgroundJob(): self
    {
        newrelic_background_job();

        return $this;
    }

    public function end(): void
    {
        newrelic_end_transaction();
    }

    private function startTransaction(): self
    {
        newrelic_start_transaction(Config::get('newrelic.app_name'));

        return $this;
    }

    private function setTransactionName(string $name): self
    {
        newrelic_name_transaction($name);

        return $this;
    }

    private function setTransactionParams(array $params = []): self
    {
        foreach ($params as $paramKey => $paramValue) {
            newrelic_add_custom_parameter($paramKey, $paramValue);
        }

        return $this;
    }
}