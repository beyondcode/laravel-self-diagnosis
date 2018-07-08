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
     * @param array $config
     * @return string
     */
    public function name(array $config): string
    {
        return trans('self-diagnosis::checks.correct_php_version_is_installed.name');
    }

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @return bool
     */
    public function check(array $config): bool
    {
        return version_compare(phpversion(), $this->getRequiredPhpVersion(), '>=');
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @param array $config
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @return string
     */
    public function message(array $config): string
    {
        return trans('self-diagnosis::checks.correct_php_version_is_installed.message', [
            'required' => $this->getRequiredPhpVersion(),
            'used' => phpversion(),
        ]);
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
