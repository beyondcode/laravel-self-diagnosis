<?php

namespace BeyondCode\SelfDiagnosis\Checks;

use BeyondCode\SelfDiagnosis\Checks\Check;

class XDebugIsNotEnabled implements Check
{
    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(array $config): string
    {
        return trans('self-diagnosis::checks.xdebug_is_not_enabled.name');
    }

    /**
     * Perform the actual verification of this check.
     *
     * @return bool
     */
    public function check(array $config): bool
    {
        return extension_loaded('xdebug') === false;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(array $config): string
    {
        return trans('self-diagnosis::checks.xdebug_is_not_enabled.message');
    }
}
