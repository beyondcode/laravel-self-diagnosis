<?php

namespace BeyondCode\SelfDiagnosis\Checks\Development;

use BeyondCode\SelfDiagnosis\Checks\Check;

class RoutesAreNotCached implements Check
{

    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Routes are not cached';
    }

    /**
     * Perform the actual verification of this check.
     *
     * @return bool
     */
    public function check(): bool
    {
        return app()->routesAreCached() === false;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Your routes should not be cached during development. Call "php artisan route:clear" to clear the route cache.';
    }
}