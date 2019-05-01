<?php

namespace BeyondCode\SelfDiagnosis\Checks;

use Dotenv\Dotenv;

class CachePrefixIsSet implements Check
{
    /**
     * The name of the check.
     *
     * @param array $config
     * @return string
     */
    public function name(array $config): string
    {
        return trans('self-diagnosis::checks.cache_prefix_is_set.name');
    }

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        if (interface_exists(\Dotenv\Environment\FactoryInterface::class)) {
            $env = Dotenv::create(base_path(), '.env');
        } else {
            $env = new Dotenv(base_path(), '.env');
        }

        $env->safeLoad();

        return in_array('CACHE_PREFIX', $env->getEnvironmentVariableNames());
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @param array $config
     * @return string
     */
    public function message(array $config): string
    {
        return trans('self-diagnosis::checks.cache_prefix_is_set.message');
    }
}
