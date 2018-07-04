<?php

namespace BeyondCode\SelfDiagnosis;

use Illuminate\Console\Command;
use BeyondCode\SelfDiagnosis\Checks\Check;

class SelfDiagnosisCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'self-diagnosis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform application self diagnosis.';

    public function handle()
    {
        $checks = config('self-diagnosis.checks');

        $max = count($checks);
        $current = 1;
        $messages = [];

        foreach ($checks as $check) {
            /** @var Check $checkClass */
            $checkClass = app($check);

            $this->output->write("<fg=yellow>Running check {$current}/{$max}:</fg=yellow> {$checkClass->name()}...  ");

            if ($checkClass->check()) {
                $this->output->write('<fg=green>✔</fg=green>');
            } else {
                $this->output->write('<fg=red>✘</fg=red>');

                $messages[] = $checkClass->message();
            }

            $this->output->write(PHP_EOL);
            $current++;
        }

        $this->output->writeln('');

        if (count($messages)) {
            $this->output->writeln('The following checks failed:');

            foreach ($messages as $message) {
                $this->output->writeln('<fg=red>'.$message.'</fg=red>');
            }
        } else {
            $this->info('Good job, looks like you are all set up.');
        }
    }
}