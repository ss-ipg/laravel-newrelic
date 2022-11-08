# Minimal Laravel NewRelic Integration

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ss-ipg/laravel-newrelic.svg?style=flat-square)](https://packagist.org/packages/ss-ipg/laravel-newrelic)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/ss-ipg/laravel-newrelic/phpunit?label=tests)](https://github.com/ss-ipg/laravel-newrelic/actions?query=workflow%3Aphpunit+branch%master)

Simple NewRelic integration that names the APP in Newrelic for both CLI and Web requests.  
It also includes a helper for the synthetics monitor and a deployment command helper.

## Installation

You can install the package via composer:

```bash
composer require ss-ipg/laravel-newrelic
```


You can publish the config file with:

```bash
php artisan vendor:publish
```

These are the ENV variables you can edit

```bash
NEWRELIC_APP_NAME=MY.domain.com
NEWRELIC_API_KEY=some-api-key
NEWRELIC_APP_ID=1234567
NEWRELIC_DEFAULT_USER=me@email.com

# Optional
NEWRELIC_ENABLED=true
NEWRELIC_MONITOR_ENABLED=true
NEWRELIC_MONITOR_ENDPOINT="newrelic/ping"
NEWRELIC_MONITOR_RESPONSE=pong
```


## Usage
Out of the box the App will automatically name the APP in NewRelic.

## Synthetics Monitor Setup
[NewRelic > Synthetic monitoring](https://one.newrelic.com/synthetics-nerdlets)   
Click on the Create Monitor > Availability

Populate the fields:
```bash
Name: Ping Monitor 
Url: https://mydomain.com/newrelic/ping

#Advanced Options
Text validation: pong
```


## Deployment Logging
After each deployment you should run the Deployment trigger

Example if you are using Envoyer
```bash
php artisan newrelic:deploy "{{message}}" --user="{{author}}" --revision="{{sha}}" --changelog="Deployed from: {{branch}}"
```

## Testing

```bash
composer test
```

## Credits

- [Igor Matkovic](https://github.com/igormatkovic)
- [JackWH/laravel-new-relic](https://github.com/JackWH/laravel-new-relic)

## License
The MIT License (MIT)
