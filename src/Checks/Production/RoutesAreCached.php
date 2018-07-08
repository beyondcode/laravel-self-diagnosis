<?php

namespace BeyondCode\SelfDiagnosis\Checks\Production;

use BeyondCode\SelfDiagnosis\Checks\Check;

class RoutesAreCached implements Check
{

    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(array $config): string
    {
        return 'Routes are cached';
    }

    /**
     * Perform the actual verification of this check.
     *
     * @return bool
     */
    public function check(array $config): bool
    {
        return app()->routesAreCached() === true;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(array $config): string
    {
        return 'Your routes should be cached in production. Call "php artisan route:cache" to create the route cache.';
    }
}
