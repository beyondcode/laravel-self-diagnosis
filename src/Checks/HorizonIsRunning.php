<?php

namespace BeyondCode\SelfDiagnosis\Checks;

use BeyondCode\SelfDiagnosis\Checks\Check;
use Illuminate\Support\Facades\Artisan;

class HorizonIsRunning implements Check
{
    private $error = null;

    /**
     * The name of the check.
     *
     * @param array $config
     * @return string
     */
    public function name(array $config): string
    {
        return trans('self-diagnosis::checks.horizon_is_running.name');
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
            Artisan::call('horizon:status');
            $output = Artisan::output();

            return strstr($output, 'Horizon is running.');
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
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
        if ($this->error !== null) {
            return trans('self-diagnosis::checks.horizon_is_running.message.unable_to_check', [
                'reason' => $this->error,
            ]);
        }

        return trans('self-diagnosis::checks.horizon_is_running.message.not_running');
    }
}
