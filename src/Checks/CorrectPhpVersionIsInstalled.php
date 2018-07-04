<?php

namespace BeyondCode\SelfDiagnosis\Checks;

class CorrectPhpVersionIsInstalled implements Check
{
    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(): string
    {
        return 'The correct PHP Version is installed';
    }

    /**
     * Perform the actual verification of this check.
     *
     * @return bool
     */
    public function check(): bool
    {
        return version_compare(phpversion(), '7.1.3', '>=');
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message() : string
    {
        return 'You do not have the required PHP version installed.';
    }
}