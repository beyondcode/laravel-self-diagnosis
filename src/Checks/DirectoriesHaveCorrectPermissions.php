<?php

namespace BeyondCode\SelfDiagnosis\Checks;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

class DirectoriesHaveCorrectPermissions implements Check
{
    /** @var Filesystem */
    private $filesystem;

    /** @var Collection */
    private $paths;

    /**
     * DirectoriesHaveCorrectPermissions constructor.
     * @param Filesystem $filesystem
     */
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
        return trans('self-diagnosis::checks.directories_have_correct_permissions.name');
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message() : string
    {
        return trans('self-diagnosis::checks.directories_have_correct_permissions.message', [
            'directories' => $this->paths->implode(PHP_EOL),
        ]);
    }

    /**
     * Perform the actual verification of this check.
     *
     * @return bool
     */
    public function check(): bool
    {
        $this->paths = Collection::make([
            storage_path(),
            base_path('bootstrap/cache')
        ]);

        $this->paths = $this->paths->reject(function ($path) {
            return $this->filesystem->isWritable($path);
        });

        return $this->paths->isEmpty();
    }
}
