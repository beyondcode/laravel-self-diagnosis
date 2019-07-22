<?php

namespace BeyondCode\SelfDiagnosis\Checks;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class UsedEnvironmentVariablesAreDefined implements Check
{
    /**
     * Stores processed var names
     *
     * @var array
     */
    private $processed = [];

    /**
     * Stores undefined var names
     *
     * @var array
     */
    public $undefined = [];

    /**
     * The amount of undefined .env variables
     *
     * @var integer
     */
    public $amount = 0;

    /**
     * The name of the check.
     *
     * @param array $config
     * @return string
     */
    public function name(array $config): string
    {
        return trans('self-diagnosis::checks.used_env_variables_are_defined.name');
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @param array $config
     * @return string
     */
    public function message(array $config): string
    {
        return trans('self-diagnosis::checks.used_env_variables_are_defined.message', [
            'amount' => $this->amount,
            'undefined' => implode(PHP_EOL, $this->undefined),
        ]);
    }

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     * @throws \Exception
     */
    public function check(array $config): bool
    {
        $paths = Collection::make(Arr::get($config, 'directories', []));

        foreach ($paths as $path) {
            $files = $this->recursiveDirSearch($path, '/.*?.php/');

            foreach ($files as $file) {
                preg_match_all(
                    '# env\((.*?)\)#',
                    str_replace(["\n", "\r"], '', file_get_contents($file)),
                    $values
                );

                if (is_array($values)) {
                    foreach ($values[1] as $value) {
                        $result = $this->getResult(
                            explode(',', str_replace(["'", '"', ' '], '', $value))
                        );

                        if (!$result) {
                            continue;
                        }

                        $this->storeResult($result);
                    }
                }
            }
        }

        return $this->amount === 0;
    }

    /**
     * Get result based on comma separated parsed env() parameters
     *
     * @param array $values
     * @return object|bool
     */
    private function getResult(array $values)
    {
        $envVar = $values[0];

        if (in_array($envVar, $this->processed)) {
            return false;
        }

        $this->processed[] = $envVar;

        return (object)[
            'envVar' => $envVar,
            'hasValue' => env($envVar) !== null,
            'hasDefault' => isset($values[1]),
        ];
    }

    /**
     * Store result and optional runtime output
     *
     * @param $result
     */
    private function storeResult($result)
    {
        if (!$result->hasValue && !$result->hasDefault) {
            $this->undefined[] = $result->envVar;
            $this->amount++;
        }
    }

    private function recursiveDirSearch(string $folder, string $pattern): array
    {
        if (!file_exists($folder)) {
            return [];
        }

        $files = new RegexIterator(
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($folder)
            ),
            $pattern, RegexIterator::GET_MATCH
        );

        $list = [];

        foreach ($files as $file) {
            $list = array_merge($list, $file);
        }

        return $list;
    }
}
