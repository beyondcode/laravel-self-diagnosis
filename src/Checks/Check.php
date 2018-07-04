<?php

namespace BeyondCode\SelfDiagnosis\Checks;

interface Check
{
    /**
     * The name of the check.
     *
     * @return string
     */
    public function name() : string;

    /**
     * Perform the actual verification of this check.
     *
     * @return bool
     */
    public function check() : bool;

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message() : string;
}