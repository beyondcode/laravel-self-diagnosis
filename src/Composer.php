<?php

namespace BeyondCode\SelfDiagnosis;

class Composer extends \Illuminate\Support\Composer
{
    public function installDryRun(string $options = null)
    {
        $process = $this->getProcess();

        $process->setCommandLine(trim($this->findComposer().' install --dry-run '.$options));

        $process->run();

        return $process->getOutput() . $process->getErrorOutput();
    }
}