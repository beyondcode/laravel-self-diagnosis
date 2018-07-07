<?php

namespace BeyondCode\SelfDiagnosis\Checks\Production;

use BeyondCode\SelfDiagnosis\Composer;
use BeyondCode\SelfDiagnosis\Checks\Check;

class ComposerWithoutDevDependenciesIsUpToDate implements Check
{
    /** @var Composer */
    private $composer;

    /** @var string */
    private $output;

    public function __construct(Composer $composer)
    {
        $this->composer = $composer;
        $this->composer->setWorkingPath(base_path());
    }

    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(): string
    {
        return trans('self-diagnosis::checks.composer_without_dev_dependencies_is_up_to_date.name');
    }

    /**
     * Perform the actual verification of this check.
     *
     * @return bool
     */
    public function check(): bool
    {
        $this->output = $this->composer->installDryRun('--no-dev');

        return str_contains($this->output, 'Nothing to install or update');
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message() : string
    {
        return trans('self-diagnosis::checks.composer_without_dev_dependencies_is_up_to_date.message', [
            'more' => $this->output,
        ]);
    }
}
