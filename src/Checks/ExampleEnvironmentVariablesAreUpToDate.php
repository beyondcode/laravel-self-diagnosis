<?php

namespace BeyondCode\SelfDiagnosis\Checks;

use Dotenv\Dotenv;
use Illuminate\Support\Collection;

class ExampleEnvironmentVariablesAreUpToDate implements Check
{
    /** @var Collection */
    private $envVariables;

    /**
     * The name of the check.
     *
     * @param array $config
     * @return string
     */
    public function name(array $config): string
    {
        return trans('self-diagnosis::checks.example_environment_variables_are_up_to_date.name');
    }

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        if (method_exists(Dotenv::class, 'createImmutable')) {
            return $this->checkForDotEnvV4();
        }

        if (interface_exists(\Dotenv\Environment\FactoryInterface::class)) {
            $examples = Dotenv::create(base_path(), '.env.example');
            $actual = Dotenv::create(base_path(), '.env');
        } else {
            $examples = new Dotenv(base_path(), '.env.example');
            $actual = new Dotenv(base_path(), '.env');
        }

        $examples->safeLoad();
        $actual->safeLoad();

        $this->envVariables = Collection::make($actual->getEnvironmentVariableNames())
            ->diff($examples->getEnvironmentVariableNames());

        return $this->envVariables->isEmpty();
    }

    /**
     * Perform the verification of this check for DotEnv v4.
     *
     * @return bool
     */
    private function checkForDotEnvV4(): bool
    {
        $examples = Dotenv::createImmutable(base_path(), '.env.example');
        $actual = Dotenv::createImmutable(base_path(), '.env');

        $this->envVariables = Collection::make($actual->safeLoad())
            ->diffKeys($examples->safeLoad())
            ->keys();

        return $this->envVariables->isEmpty();
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @param array $config
     * @return string
     */
    public function message(array $config): string
    {
        return trans('self-diagnosis::checks.example_environment_variables_are_up_to_date.message', [
            'variables' => $this->envVariables->implode(PHP_EOL),
        ]);
    }
}
