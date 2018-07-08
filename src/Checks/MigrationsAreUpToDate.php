<?php

namespace BeyondCode\SelfDiagnosis\Checks;

use Illuminate\Support\Facades\Artisan;

class MigrationsAreUpToDate implements Check
{
    private $databaseError = null;

    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(array $config): string
    {
        return 'The migrations are up to date';
    }

    /**
     * Perform the actual verification of this check.
     *
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
     * @return string
     */
    public function message(array $config): string
    {
        if ($this->databaseError !== null) {
            return 'Unable to check for migrations: ' . $this->databaseError;
        }
        return 'You need to update your migrations. Call "php artisan migrate".';
    }
}
