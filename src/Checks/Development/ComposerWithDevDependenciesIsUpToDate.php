<?php

namespace BeyondCode\SelfDiagnosis\Checks\Development;

use BeyondCode\SelfDiagnosis\Composer;
use BeyondCode\SelfDiagnosis\Checks\Check;

class ComposerWithDevDependenciesIsUpToDate implements Check
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
    public function name(array $config): string
    {
        return 'Composer dependencies are up to date with the composer.lock file.';
    }

    /**
     * Perform the actual verification of this check.
     *
     * @return bool
     */
    public function check(array $config): bool
    {
        $this->output = $this->composer->installDryRun();

        return str_contains($this->output, 'Nothing to install or update');
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(array $config): string
    {
        return 'Your composer dependencies are not up to date. Call "composer install".' . $this->output;
    }
}
