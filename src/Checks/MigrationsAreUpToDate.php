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
    public function name(): string
    {
        return 'The migrations are up to date';
    }

    /**
     * Perform the actual verification of this check.
     *
     * @return bool
     */
    public function check(): bool
    {
        try {
            Artisan::call('migrate', ['--pretend' => 'true']);
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
    public function message(): string
    {
        if ($this->databaseError !== null) {
            return 'Unable to check for migrations: ' . $this->databaseError;
        }
        return 'You need to update your migrations. Call "php artisan migrate".';
    }
}