<?php

namespace BeyondCode\SelfDiagnosis\Checks;

interface Check
{
    /**
     * The name of the check.
     *
     * @param array $config
     * @return string
     */
    public function name(array $config): string;

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool;

    /**
     * The error message to display in case the check does not pass.
     *
     * @param array $config
     * @return string
     */
    public function message(array $config): string;
}
