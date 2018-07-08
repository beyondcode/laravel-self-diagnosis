# Perform Self-Diagnosis Tests On Your Laravel Application

[![Latest Version on Packagist](https://img.shields.io/packagist/v/beyondcode/laravel-self-diagnosis.svg?style=flat-square)](https://packagist.org/packages/beyondcode/laravel-self-diagnosis)
[![Build Status](https://img.shields.io/travis/beyondcode/laravel-self-diagnosis/master.svg?style=flat-square)](https://travis-ci.org/beyondcode/laravel-self-diagnosis)
[![Quality Score](https://img.shields.io/scrutinizer/g/beyondcode/laravel-self-diagnosis.svg?style=flat-square)](https://scrutinizer-ci.com/g/beyondcode/laravel-self-diagnosis)
[![Total Downloads](https://img.shields.io/packagist/dt/beyondcode/laravel-self-diagnosis.svg?style=flat-square)](https://packagist.org/packages/beyondcode/laravel-self-diagnosis)

This package allows you to run self-diagnosis tests on your Laravel application. It comes with multiple checks out of the box and allows you to add custom checks yourself.

Here is an example output of the command:

![All Checks passed](https://beyondco.de/github/self-diagnosis/checks_green.png)

## Included checks

- Is the APP_KEY set?
- Are your composer dependencies up to date with the composer.lock file?
- Do you have the correct PHP version installed?
- Do you have the correct PHP extensions installed?
- Can a connection to the database be established?
- Do the `storage` and `bootstrap/cache` directories have the correct permissions?
- Does the `.env` file exist?
- Are there environment variables that exist in `.env.example` but not in `.env`?
- Are there any migrations that need to be run?
- Is the storage directory linked?

### Development environment checks

- Is the configuration not cached?
- Are the routes not cached?

### Production environment checks

- Is the configuration cached?
- Are the routes cached?
- Is the xdebug PHP extension disabled?
- Is APP_DEBUG set to false?

## Installation

You can install the package via composer:

```bash
composer require beyondcode/laravel-self-diagnosis
```

If you're using Laravel 5.5+ the `SelfDiagnosisServiceProvider` will be automatically registered for you. 

## Usage

Just call the artisan command to start the checks:

```bash
php artisan self-diagnosis
```

### Customization

You can publish the configuration file, that contains all available checks using:

```bash
php artisan vendor:publish --provider=BeyondCode\\SelfDiagnosis\\SelfDiagnosisServiceProvider
```

This will publish a `self-diagnosis.php` file in your `config` folder. This file contains all the checks that will be performed on your application.

```php
<?php

return [

    /*
     * A list of environment aliases mapped to the actual environment configuration.
     */
    'environment_aliases' => [
        'prod' => 'production',
        'live' => 'production',
    ],

    /*
     * Common checks that will be performed on all environments.
     */
    'checks' => [
        \BeyondCode\SelfDiagnosis\Checks\AppKeyIsSet::class,
        \BeyondCode\SelfDiagnosis\Checks\CorrectPhpVersionIsInstalled::class,
        \BeyondCode\SelfDiagnosis\Checks\DatabaseCanBeAccessed::class,
        \BeyondCode\SelfDiagnosis\Checks\DirectoriesHaveCorrectPermissions::class,
        \BeyondCode\SelfDiagnosis\Checks\EnvFileExists::class,
        \BeyondCode\SelfDiagnosis\Checks\ExampleEnvironmentVariablesAreSet::class,
        \BeyondCode\SelfDiagnosis\Checks\MigrationsAreUpToDate::class,
        \BeyondCode\SelfDiagnosis\Checks\PhpExtensionsAreInstalled::class,
        \BeyondCode\SelfDiagnosis\Checks\StorageDirectoryIsLinked::class,
    ],

    /*
     * Environment specific checks that will only be performed for the corresponding environment.
     */
    'environment_checks' => [
        'development' => [
            \BeyondCode\SelfDiagnosis\Checks\ComposerWithDevDependenciesIsUpToDate::class,
            \BeyondCode\SelfDiagnosis\Checks\ConfigurationIsNotCached::class,
            \BeyondCode\SelfDiagnosis\Checks\RoutesAreNotCached::class,
        ],
        'production' => [
            \BeyondCode\SelfDiagnosis\Checks\ComposerWithoutDevDependenciesIsUpToDate::class,
            \BeyondCode\SelfDiagnosis\Checks\ConfigurationIsCached::class,
            \BeyondCode\SelfDiagnosis\Checks\DebugModeIsNotEnabled::class,
            \BeyondCode\SelfDiagnosis\Checks\RoutesAreCached::class,
            \BeyondCode\SelfDiagnosis\Checks\XDebugIsNotEnabled::class,
        ],
    ],

];
```

### Custom Checks

You can create custom checks, by implementing the [`BeyondCode\SelfDiagnosis\Checks\Check`](src/Checks/Check.php) interface and adding the class to the config file.
Like this:

```php
<?php

use BeyondCode\SelfDiagnosis\Checks\Check;

class MyCustomCheck implements Check
{
    /**
     * The name of the check.
     *
     * @param array $config
     * @return string
     */
    public function name(array $config): string
    {
        return 'My custom check.';
    }

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        return true;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @param array $config
     * @return string
     */
    public function message(array $config): string
    {
        return 'This is the error message that users see if "check" returns false.';
    }
}
```

 
### Example Output


![Some Checks failed](https://beyondco.de/github/self-diagnosis/checks_red.png)

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email marcel@beyondco.de instead of using the issue tracker.

## Credits

- [Marcel Pociot](https://github.com/mpociot)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
