<?php

namespace BeyondCode\SelfDiagnosis\Checks\Production;

use BeyondCode\SelfDiagnosis\Checks\Check;

class XDebugIsNotEnabled implements Check
{

    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(): string
    {
        return 'The xdebug extension is not active.';
    }

    /**
     * Perform the actual verification of this check.
     *
     * @return bool
     */
    public function check(): bool
    {
        return extension_loaded('xdebug') === false;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        return 'You should not have the "xdebug" PHP extension activated in production.';
    }
}
