<?php

namespace BeyondCode\SelfDiagnosis\Checks;

use BeyondCode\SelfDiagnosis\Composer;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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
     * @param array $config
     * @return string
     */
    public function name(array $config): string
    {
        return trans('self-diagnosis::checks.composer_with_dev_dependencies_is_up_to_date.name');
    }

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        $additionalOptions = Arr::get($config, 'additional_options', '');

        $this->output = $this->composer->installDryRun($additionalOptions);

        return Str::contains($this->output, [
            'Nothing to install or update',
            'Nothing to install, update or remove',
            'Package operations: 0 installs, 0 updates, 0 removals'
        ]);
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @param array $config
     * @return string
     */
    public function message(array $config): string
    {
        return trans('self-diagnosis::checks.composer_with_dev_dependencies_is_up_to_date.message', [
            'more' => $this->output,
        ]);
    }
}
