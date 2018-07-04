<?php

namespace BeyondCode\SelfDiagnosis;

class Composer extends \Illuminate\Support\Composer
{
    public function installDryRun()
    {
        $process = $this->getProcess();

        $process->setCommandLine(trim($this->findComposer().' install --dry-run'));

        $process->run();

        return $process->getOutput() . $process->getErrorOutput();
    }
}