<?php

namespace BeyondCode\SelfDiagnosis\Checks\Development;

use BeyondCode\SelfDiagnosis\Checks\Check;

class ConfigurationIsNotCached implements Check
{

    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(array $config): string
    {
        return 'Configuration is not cached';
    }

    /**
     * Perform the actual verification of this check.
     *
     * @return bool
     */
    public function check(array $config): bool
    {
        return app()->configurationIsCached() === false;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(array $config): string
    {
        return 'Your configuration files should not be cached during development. Call "php artisan config:clear" to clear the config cache.';
    }
}
