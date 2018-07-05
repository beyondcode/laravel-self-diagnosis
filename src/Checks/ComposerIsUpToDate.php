<?php

namespace BeyondCode\SelfDiagnosis\Checks;

use BeyondCode\SelfDiagnosis\Composer;

class ComposerIsUpToDate implements Check
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
        return 'Composer dependencies are up to date';
    }

    /**
     * Perform the actual verification of this check.
     *
     * @return bool
     */
    public function check(): bool
    {
        $this->output = $this->composer->installDryRun();

        return str_contains($this->output, 'Nothing to install or update');
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message() : string
    {
        return 'Your composer dependencies are not up to date. Call "composer install".' . $this->output;
    }
}
