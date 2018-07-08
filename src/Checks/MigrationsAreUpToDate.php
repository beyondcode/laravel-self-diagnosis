<?php

namespace BeyondCode\SelfDiagnosis\Checks;

use Illuminate\Support\Facades\Artisan;

class MigrationsAreUpToDate implements Check
{
    private $databaseError = null;

    /**
     * The name of the check.
     *
     * @param array $config
     * @return string
     */
    public function name(array $config): string
    {
        return trans('self-diagnosis::checks.migrations_are_up_to_date.name');
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
            Artisan::call('migrate', ['--pretend' => 'true', '--force' => 'true']);
            $output = Artisan::output();
            return strstr($output, 'Nothing to migrate.');
        } catch (\PDOException $e) {
            $this->databaseError = $e->getMessage();
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
        if ($this->databaseError !== null) {
            return trans('self-diagnosis::checks.migrations_are_up_to_date.message.unable_to_check', [
                'reason' => $this->databaseError,
            ]);
        }
        return trans('self-diagnosis::checks.migrations_are_up_to_date.message.need_to_migrate');
    }
}
