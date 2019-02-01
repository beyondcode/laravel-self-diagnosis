<?php

namespace BeyondCode\SelfDiagnosis\Checks;

use BeyondCode\SelfDiagnosis\SystemFunctions;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class LocalesAreInstalled implements Check
{
    /** @var Collection */
    protected $missingLocales;

    /** @var string|null */
    protected $message;

    /** @var SystemFunctions */
    protected $systemFunctions;

    /**
     * LocalesAreInstalled constructor.
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
        return trans('self-diagnosis::checks.locales_are_installed.name');
    }

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        $this->missingLocales = new Collection(Arr::get($config, 'required_locales', []));
        if ($this->missingLocales->isEmpty()) {
            return true;
        }

        if (!$this->systemFunctions->isFunctionAvailable('shell_exec')) {
            $this->message = trans('self-diagnosis::checks.locales_are_installed.message.shell_exec_not_available');
            return false;
        }

        if ($this->systemFunctions->isWindowsOperatingSystem()) {
            $this->message = trans('self-diagnosis::checks.locales_are_installed.message.cannot_run_on_windows');
            return false;
        }

        $locales = $this->systemFunctions->callShellExec('locale -a');
        if ($locales === null || $locales === '') {
            $this->message = trans('self-diagnosis::checks.locales_are_installed.message.locale_command_not_available');
            return false;
        }

        $locales = explode("\n" , $locales);

        $this->missingLocales = $this->missingLocales->reject(function ($loc) use ($locales) {
            return in_array($loc, $locales);
        });

        return $this->missingLocales->isEmpty();
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

        return trans('self-diagnosis::checks.locales_are_installed.message.missing_locales', [
            'locales' => $this->missingLocales->implode(PHP_EOL),
        ]);
    }
}
