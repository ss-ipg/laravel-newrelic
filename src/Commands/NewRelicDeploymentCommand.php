<?php

namespace SecureSpace\LaravelNewRelic\Commands;

use SecureSpace\LaravelNewRelic\Exceptions\MissingNewrelicApiKeyException;
use SecureSpace\LaravelNewRelic\Exceptions\MissingNewrelicAppIdException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class NewRelicDeploymentCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    public $signature = 'newrelic:deploy
                        {description? : A description of the change}
                        {--changelog= : Changelog description}
                        {--revision= : The git revision hash}
                        {--user= : User to attribute the deploy to}';

    /**
     * The console command description.
     */
    public $description = 'Notify New Relic of a new deployment.';

    /**
     * Execute the console command.
     *
     * @throws MissingNewrelicApiKeyException|MissingNewrelicAppIdException
     */
    public function handle(): int
    {
        if (! $apiKey = Config::get('laravel-newrelic.deployments.api_key')) {
            throw new MissingNewrelicApiKeyException('New Relic API key not found.');
        }

        $revision = $this->option('revision') ?? $this->detectRevision();
        $changelog = $this->option('changelog') ?? '';
        $user = $this->option('user') ?: Config::get('laravel-newrelic.deployments.default_user');

        $request = Http::withHeaders(['Api-Key' => $apiKey])
                       ->asJson()
                       ->post($this->getDeploymentUrl(), [
                           'deployment' => [
                               'revision'    => $revision,
                               'changelog'   => $changelog,
                               'description' => $this->argument('description'),
                               'user'        => $user,
                           ],
                       ]);

        if ($request->successful()) {
            $this->info('Notified New Relic!');

            return parent::SUCCESS;
        }

        $this->warn('Could not notify New Relic [HTTP ' . $request->status() . ']');
        $this->warn('See: https://rpm.newrelic.com/api/explore/application_deployments/create');

        return parent::FAILURE;
    }

    /**
     * Attempt to auto-detect the current git revision hash.
     */
    public function detectRevision(): ?string
    {
        try {
            return trim(exec('git log --pretty="%H" -n1 HEAD'));
        } catch (\Throwable $throwable) {
            $this->warn('Could not auto-detect revision hash: ' . $throwable->getMessage());
        }

        return null;
    }

    /**
     * @throws MissingNewrelicAppIdException
     */
    protected function getDeploymentUrl(): string
    {
        if (! $appId = Config::get('laravel-newrelic.deployments.app_id')) {
            throw new MissingNewrelicAppIdException('New Relic App ID not found.');
        }
        $endpoint = rtrim(Config::get('laravel-newrelic.deployments.endpoint'), '/');

        return sprintf('%s/applications/%s/deployments.json', $endpoint, $appId);
    }
}
