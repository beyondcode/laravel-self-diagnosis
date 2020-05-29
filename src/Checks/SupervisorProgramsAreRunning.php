<?php

namespace BeyondCode\SelfDiagnosis\Checks;

use BeyondCode\SelfDiagnosis\SystemFunctions;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class SupervisorProgramsAreRunning implements Check
{
    /** @var Collection */
    protected $notRunningPrograms;

    /** @var string|null */
    protected $message;

    /** @var SystemFunctions */
    protected $systemFunctions;

    /** @var string */
    protected const REGEX_SUPERVISORCTL_STATUS = '/^(\S+)\s+RUNNING\s+pid\s+(\d+),\s+uptime\s+(\d+):(\d+):(\d+)$/';

    /**
     * SupervisorProgramsAreRunning constructor.
     *
     * @param SystemFunctions $systemFunctions
     */
    public function __construct(SystemFunctions $systemFunctions)
    {
        $this->systemFunctions = $systemFunctions;
    }

    /**
     * The name of the check.
     *
     * @param array $config
     * @return string
     */
    public function name(array $config): string
    {
        return trans('self-diagnosis::checks.supervisor_programs_are_running.name');
    }

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        $this->notRunningPrograms = new Collection(Arr::get($config, 'programs', []));
        if ($this->notRunningPrograms->isEmpty()) {
            return true;
        }

        if (!$this->systemFunctions->isFunctionAvailable('shell_exec')) {
            $this->message = trans('self-diagnosis::checks.supervisor_programs_are_running.message.shell_exec_not_available');
            return false;
        }

        if ($this->systemFunctions->isWindowsOperatingSystem()) {
            $this->message = trans('self-diagnosis::checks.supervisor_programs_are_running.message.cannot_run_on_windows');
            return false;
        }

        $programs = $this->systemFunctions->callShellExec('supervisorctl status');
        if ($programs === null || $programs === '') {
            $this->message = trans('self-diagnosis::checks.supervisor_programs_are_running.message.supervisor_command_not_available');
            return false;
        }

        $restartedWithin = Arr::get($config, 'restarted_within', 0);
        $programs = explode("\n" , $programs);
        foreach ($programs as $program) {
            /*
             * Capture groups of regex:
             * (program name) (process id) (uptime hours) (minutes) (seconds)
             */
            $isMatch = preg_match(self::REGEX_SUPERVISORCTL_STATUS, trim($program), $matches);
            if ($isMatch) {
                if ($restartedWithin > 0) {
                    $totalSeconds = $matches[3] * 3600 + $matches[4] * 60 + $matches[5];
                    if ($totalSeconds > $restartedWithin) {
                        continue;
                    }
                }

                $this->notRunningPrograms = $this->notRunningPrograms->reject(function ($item) use ($matches) {
                    return $item === $matches[1];
                });
            }
        }

        return $this->notRunningPrograms->isEmpty();
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @param array $config
     * @return string
     */
    public function message(array $config): string
    {
        if ($this->message) {
            return $this->message;
        }

        return trans('self-diagnosis::checks.supervisor_programs_are_running.message.not_running_programs', [
            'programs' => $this->notRunningPrograms->implode(PHP_EOL),
        ]);
    }
}
