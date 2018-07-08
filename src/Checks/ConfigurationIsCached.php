<?php

namespace BeyondCode\SelfDiagnosis\Checks;

use BeyondCode\SelfDiagnosis\Checks\Check;

class ConfigurationIsCached implements Check
{

    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(array $config): string
    {
        return 'Configuration is cached';
    }

    /**
     * Perform the actual verification of this check.
     *
     * @return bool
     */
    public function check(array $config): bool
    {
        return app()->configurationIsCached() === true;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(array $config): string
    {
        return 'Your configuration files should be cached in production. Call "php artisan config:cache" to cache the configuration.';
    }
}
