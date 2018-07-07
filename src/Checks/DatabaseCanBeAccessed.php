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
        return trans('self-diagnosis::checks.database_can_be_accessed.name');
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
        return trans('self-diagnosis::checks.database_can_be_accessed.message', [
            'error' => $this->message,
        ]);
    }
}