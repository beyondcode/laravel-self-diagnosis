<?php

namespace BeyondCode\SelfDiagnosis\Checks;

use Dotenv\Dotenv;
use Illuminate\Support\Collection;

class ExampleEnvironmentVariablesAreSet implements Check
{
    /** @var Collection */
    private $envVariables;

    /**
     * Construct the check, pre set the private variable.
     */
    public function __construct()
    {
        $this->envVariables = new Collection();
    }

    /**
     * The name of the check.
     *
     * @param array $config
     * @return string
     */
    public function name(array $config): string
    {
        return trans('self-diagnosis::checks.example_environment_variables_are_set.name');
    }

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        $examples = new Dotenv(base_path(), '.env.example');
        $examples->safeLoad();

        $actual = new Dotenv(base_path(), '.env');
        $actual->safeLoad();

        $this->envVariables = Collection::make($examples->getEnvironmentVariableNames())
            ->diff($actual->getEnvironmentVariableNames());

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
        return trans('self-diagnosis::checks.example_environment_variables_are_set.message', [
            'variables' => $this->envVariables->implode(PHP_EOL),
        ]);
    }
}
