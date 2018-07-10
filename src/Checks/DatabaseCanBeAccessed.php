<?php

namespace BeyondCode\SelfDiagnosis\Checks;

use Illuminate\Support\Facades\DB;

class DatabaseCanBeAccessed implements Check
{
    /**
     * @var string
     */
    private $message;

    /**
     * The name of the check.
     *
     * @param array $config
     * @return string
     */
    public function name(array $config): string
    {
        return trans('self-diagnosis::checks.database_can_be_accessed.name');
    }

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        try {
            if (array_get($config, 'default_connection', true)) {
                DB::connection()->getPdo();
            }

            foreach (array_get($config, 'connections', []) as $connection) {
                DB::connection($connection)->getPdo();
            }

            return true;
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
        }
        return false;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @param array $config
     * @return string
     */
    public function message(array $config): string
    {
        return trans('self-diagnosis::checks.database_can_be_accessed.message', [
            'error' => $this->message,
        ]);
    }
}
