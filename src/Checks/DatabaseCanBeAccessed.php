<?php

namespace BeyondCode\SelfDiagnosis\Checks;

use DB;

class DatabaseCanBeAccessed implements Check
{
    private $message;

    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(): string
    {
        return 'The database can be accessed';
    }

    /**
     * Perform the actual verification of this check.
     *
     * @return bool
     */
    public function check(): bool
    {
        try {
            DB::connection()->getPdo();

            return true;
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
        }
        return false;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message() : string
    {
        return 'The database can not be accessed: '.$this->message;
    }
}