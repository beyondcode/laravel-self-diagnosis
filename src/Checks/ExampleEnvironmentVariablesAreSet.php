<?php

namespace BeyondCode\SelfDiagnosis\Checks;

use Dotenv\Dotenv;
use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;

class ExampleEnvironmentVariablesAreSet implements Check
{
    /** @var Collection */
    private $envVariables;

    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(array $config): string
    {
        return 'The example environment variables are set';
    }

    /**
     * Perform the actual verification of this check.
     *
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
     * @return string
     */
    public function message(array $config): string
    {
        return 'These example environment variables are missing in your .env file, but are defined in your .env.example: '.PHP_EOL.$this->envVariables->implode(PHP_EOL);
    }
}
