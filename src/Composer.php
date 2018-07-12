<?php

namespace BeyondCode\SelfDiagnosis;

class Composer extends \Illuminate\Support\Composer
{
    /**
     * @param string|null $options
     *
     * @return string
     */
    public function installDryRun(string $options = null)
    {
        $process = $this->getProcess();

        $process->setCommandLine(trim($this->findComposer().' install --dry-run '.$options));

        $process->run();

        return $process->getOutput() . $process->getErrorOutput();
    }
}
