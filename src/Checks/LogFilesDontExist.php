<?php

namespace BeyondCode\SelfDiagnosis\Checks;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class LogFilesDontExist implements Check
{
    /** @var Collection */
    protected $ignoreFiles;

    /** @var Collection */
    protected $logFiles;

    /**
     * The name of the check.
     *
     * @param array $config
     * @return string
     */
    public function name(array $config): string
    {
        return trans('self-diagnosis::checks.log_files_dont_exist.name');
    }

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        $this->ignoreFiles = new Collection(array_get($config, 'ignore_files', []));
        
        $logHandlers = Collection::make(app('log')->driver()->getLogger()->getHandlers());
        $fileInfo = pathinfo($logHandlers->first()->getUrl());
        $globPattern = Str::finish($fileInfo['dirname'], '/*.' . $fileInfo['extension']);
        $this->logFiles = Collection::make(glob($globPattern))->reject(function ($file) {
            return Str::is($this->ignoreFiles->toArray(), pathinfo($file, PATHINFO_BASENAME));
        });

        return $this->logFiles->isEmpty();
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @param array $config
     * @return string
     */
    public function message(array $config): string
    {
        return trans('self-diagnosis::checks.log_files_dont_exist.message', [
            'files' => $this->logFiles->implode(PHP_EOL)
        ]);
    }
}
