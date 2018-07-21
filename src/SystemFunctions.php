<?php

namespace BeyondCode\SelfDiagnosis;

/**
 * Proxy class for system functions.
 *
 * Class SystemFunctions
 * @package BeyondCode\SelfDiagnosis
 */
class SystemFunctions
{
    /**
     * Performs a shell_exec call. Acts as proxy.
     *
     * @param string $command
     * @return null|string
     */
    public function callShellExec(string $command): ?string
    {
        return shell_exec($command);
    }

    /**
     * Checks if a function is defined and not disabled.
     *
     * @param string $function
     * @return bool
     */
    public function isFunctionAvailable(string $function): bool
    {
        return is_callable($function) && false === stripos(ini_get('disable_functions'), $function);
    }

    /**
     * Checks if we are running on a windows operating system.
     *
     * @return bool
     */
    public function isWindowsOperatingSystem(): bool
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
}
