<?php

namespace BeyondCode\SelfDiagnosis\Checks;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class EnvVariablesExists implements Check
{
    /**
     * The empty results of performed scan
     *
     * @var array
     */
    public $empty = 0;

    /**
     * The name of the check.
     *
     * @param array $config
     * @return string
     */
    public function name(array $config): string
    {
        return trans('self-diagnosis::checks.env_variables_exist.name');
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @param array $config
     * @return string
     */
    public function message(array $config): string
    {
        return trans('self-diagnosis::checks.env_variables_exist.message', [
            'empty' => $this->empty,
        ]);
    }

    /**
     * Directories to start recursive search for env()'s from
     * Defaults to config_path()
     *
     * @var string $dir
     */
    public $paths;

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     * @throws \Exception
     */
    public function check(array $config): bool
    {
        $this->paths = Collection::make(Arr::get($config, 'directories', []));

        foreach ($this->paths as $path) {
            $files = $this->recursiveDirSearch($path,  '/.*?.php/');

            foreach ($files as $file) {
                preg_match_all(
                    '#env\((.*?)\)#',
                    str_replace(["\n", "\r"], '', file_get_contents($file)),
                    $values
                );

                if (is_array($values)) {
                    foreach ($values[1] as $value) {
                        $result = $this->getResult(
                            explode(',', str_replace(["'", '"', ' '], '', $value))
                        );

                        $this->storeResult($result);
                    }
                }
            }
        }

        return $this->empty === 0;
    }

    /**
     * Get result based on comma separated parsed env() parameters
     *
     * @param array $values
     * @return object
     */
    private function getResult(array $values)
    {
        return (object)[
            'envVar' => $values[0],
            'hasValue' => (bool)env($values[0]),
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
        if (! $result->hasValue && ! $result->hasDefault) {
            $this->empty++;
        }
    }

    private function recursiveDirSearch(string $folder, string $pattern): array
    {
        if (! file_exists($folder)) {
            return [];
        }

        $files = new RegexIterator(
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($folder)
            ),
            $pattern, RegexIterator::GET_MATCH
        );

        $list = [];

        foreach($files as $file) {
            $list = array_merge($list, $file);
        }

        return $list;
    }
}
