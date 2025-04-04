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
        $examples = Dotenv::createMutable(base_path(), '.env.example');
        $examples = $examples->safeLoad();

        $actual = Dotenv::createMutable(base_path(), '.env');
        $actual = $actual->safeLoad();

        $this->envVariables = Collection::make($actual)
            ->diffKeys($examples)
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
