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
    public function name(): string
    {
        return trans('self-diagnosis::checks.configuration_is_not_cached.name');
    }

    /**
     * Perform the actual verification of this check.
     *
     * @return bool
     */
    public function check(): bool
    {
        return app()->configurationIsCached() === false;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        return trans('self-diagnosis::checks.configuration_is_not_cached.message');
    }
}
