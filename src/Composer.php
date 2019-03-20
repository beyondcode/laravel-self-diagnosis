<?php

namespace BeyondCode\SelfDiagnosis;

class Composer extends \Illuminate\Support\Composer
{
    public function installDryRun(string $options = null)
    {
        $composer = $this->findComposer();

        $command = array_merge(
            (array) $composer,
            ['install', '--dry-run'],
            array_filter(array_map('trim', explode(' ', $options)))
        );

        if (is_array($composer)) {
            $process = $this->getProcess($command);
        } else {
            $process = $this->getProcess();
            $process->setCommandLine(trim(implode(' ', $command)));
        }

        $process->run();

        return $process->getOutput() . $process->getErrorOutput();
    }
}