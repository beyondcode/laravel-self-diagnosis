<?php

namespace BeyondCode\SelfDiagnosis\Checks;

class AppKeyIsSet implements Check
{
    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(): string
    {
        return 'App key is set';
    }

    /**
     * Perform the actual verification of this check.
     *
     * @return bool
     */
    public function check(): bool
    {
        return config('app.key') !== null;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message() : string
    {
        return 'The application key is not set. Call "php artisan key:generate" to create it.';
    }
}