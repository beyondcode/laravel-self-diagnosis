<?php

namespace BeyondCode\SelfDiagnosis\Checks;

use Illuminate\Filesystem\Filesystem;
use Composer\Semver\Semver;
use Illuminate\Support\Arr;

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
        // we dont use the phpversion function because that adds more data to the number (Like: -1+ubuntu16.04)
        // that conflicts with the semver check
        return Semver::satisfies(
            PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION . '.' . PHP_RELEASE_VERSION,
            $this->getRequiredPhpConstraint()
        );
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
            'required' => $this->getRequiredPhpConstraint(),
            'used' => phpversion(),
        ]);
    }

    /**
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getRequiredPhpConstraint()
    {
        $composer = json_decode($this->filesystem->get(base_path('composer.json')), true);
        return Arr::get($composer, 'require.php');
    }
}
