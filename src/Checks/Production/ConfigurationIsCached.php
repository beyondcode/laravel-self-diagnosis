<?php

namespace BeyondCode\SelfDiagnosis\Checks\Production;

use BeyondCode\SelfDiagnosis\Checks\Check;

class ConfigurationIsCached implements Check
{

    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(): string
    {
        return trans('self-diagnosis::checks.configuration_is_cached.name');
    }

    /**
     * Perform the actual verification of this check.
     *
     * @return bool
     */
    public function check(): bool
    {
        return app()->configurationIsCached() === true;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        return trans('self-diagnosis::checks.configuration_is_cached.message');
    }
}
