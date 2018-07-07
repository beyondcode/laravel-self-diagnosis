<?php

namespace BeyondCode\SelfDiagnosis\Checks\Production;

use BeyondCode\SelfDiagnosis\Checks\Check;

class DebugModeIsNotEnabled implements Check
{

    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(): string
    {
        return trans('self-diagnosis::checks.debug_mode_is_not_enabled.name');
    }

    /**
     * Perform the actual verification of this check.
     *
     * @return bool
     */
    public function check(): bool
    {
        return config('app.debug') === false;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        return trans('self-diagnosis::checks.debug_mode_is_not_enabled.message');
    }
}
