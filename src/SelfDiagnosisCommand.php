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

    private $messages = [];

    public function handle()
    {
        $this->runChecks(config('self-diagnosis.checks', []), trans('self-diagnosis::commands.self_diagnosis.common_checks'));

        $environmentChecks = config('self-diagnosis.development', []);
        if (in_array(app()->environment(), config('self-diagnosis.productionEnvironments'))) {
            $environmentChecks = config('self-diagnosis.production', []);
        }

        $this->runChecks($environmentChecks, trans('self-diagnosis::commands.self_diagnosis.environment_specific_checks', ['environment' => app()->environment()]));

        if (count($this->messages)) {
            $this->output->writeln(trans('self-diagnosis::commands.self_diagnosis.failed_checks'));

            foreach ($this->messages as $message) {
                $this->output->writeln('<fg=red>'.$message.'</fg=red>');
                $this->output->writeln('');
            }
        } else {
            $this->info(trans('self-diagnosis::commands.self_diagnosis.success'));
        }
    }

    protected function runChecks(array $checks, string $title)
    {
        $max = count($checks);
        $current = 1;

        $this->output->writeln('|-------------------------------------');
        $this->output->writeln('| '.$title);
        $this->output->writeln('|-------------------------------------');

        foreach ($checks as $check) {
            $checkClass = app($check);

            $this->output->write(trans('self-diagnosis::commands.self_diagnosis.running_check', [
                'current' => $current,
                'max' => $max,
                'name' => $checkClass->name(),
            ]));

            $this->runCheck($checkClass);

            $current++;
        }

        $this->output->writeln('');
    }

    protected function runCheck(Check $check)
    {
        if ($check->check()) {
            $this->output->write('<fg=green>✔</fg=green>');
        } else {
            $this->output->write('<fg=red>✘</fg=red>');

            $this->messages[] = $check->message();
        }

        $this->output->write(PHP_EOL);
    }
}
