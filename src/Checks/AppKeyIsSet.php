<?php

namespace BeyondCode\SelfDiagnosis\Checks;

class AppKeyIsSet implements Check
{
    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(array $config): string
    {
        return trans('self-diagnosis::checks.app_key_is_set.name');
    }

    /**
     * Perform the actual verification of this check.
     *
     * @return bool
     */
    public function check(array $config): bool
    {
        return config('app.key') !== null;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(array $config): string
    {
        return trans('self-diagnosis::checks.app_key_is_set.message');
    }
}
