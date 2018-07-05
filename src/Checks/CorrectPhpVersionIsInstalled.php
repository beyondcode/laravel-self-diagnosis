<?php

namespace BeyondCode\SelfDiagnosis\Checks;

use Illuminate\Filesystem\Filesystem;

class CorrectPhpVersionIsInstalled implements Check
{
    /** @var Filesystem */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(): string
    {
        return 'The correct PHP version is installed';
    }

    /**
     * Perform the actual verification of this check.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @return bool
     */
    public function check(): bool
    {
        return version_compare(phpversion(), $this->getRequiredPhpVersion(), '>=');
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @return string
     */
    public function message() : string
    {
        return 'You do not have the required PHP version installed.'.PHP_EOL.'Required: '.$this->getRequiredPhpVersion().PHP_EOL.'Used: '.phpversion();
    }

    /**
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getRequiredPhpVersion()
    {
        $composer = json_decode($this->filesystem->get(base_path('composer.json')), true);
        $versionString = array_get($composer, 'require.php');

        return str_replace(['^', '~', '<', '>', '='], '', $versionString);
    }
}
