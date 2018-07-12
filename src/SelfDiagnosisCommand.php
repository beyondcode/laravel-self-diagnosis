<?php

namespace BeyondCode\SelfDiagnosis;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use BeyondCode\SelfDiagnosis\Checks\Check;
use Illuminate\Contracts\Foundation\Application;

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

    /**
     * Messages stack.
     *
     * @var string[]
     */
    private $messages = [];

    /**
     * Execute the console command.
     *
     * @param Application $app
     *
     * @return int
     */
    public function handle(Application $app)
    {
        $this->runChecks(
            config('self-diagnosis.checks', []),
            trans('self-diagnosis::commands.self_diagnosis.common_checks')
        );

        $environmentChecks = config('self-diagnosis.environment_checks.' . $app->environment(), []);
        if (empty($environmentChecks) && array_key_exists($app->environment(), config('self-diagnosis.environment_aliases'))) {
            $environment = config('self-diagnosis.environment_aliases.' . $app->environment());
            $environmentChecks = config('self-diagnosis.environment_checks.' . $environment, []);
        }

        $this->runChecks($environmentChecks, trans('self-diagnosis::commands.self_diagnosis.environment_specific_checks', ['environment' => $app->environment()]));

        if (\count($this->messages)) {
            $this->error(trans('self-diagnosis::commands.self_diagnosis.failed_checks'));

            foreach ($this->messages as $message) {
                $this->output->writeln('<fg=red>'.$message.'</fg=red>' . PHP_EOL);
            }

            return 1; // Exit WITH error (EXIT_CODE != 0)
        }

        $this->info(trans('self-diagnosis::commands.self_diagnosis.success'));

        return 0; // Exit without error (EXIT_CODE = 0)
    }

    /**
     * Run checks.
     *
     * @param array  $checks
     * @param string $title
     */
    protected function runChecks(array $checks, string $title)
    {
        $max = \count($checks);
        $current = 1;

        $this->drawBox($title);

        foreach ($checks as $check => $config) {
            if (is_numeric($check)) {
                $check = $config;
                $config = [];
            }

            $checkClass = app($check);

            $this->output->write(trans('self-diagnosis::commands.self_diagnosis.running_check', [
                'current' => $current,
                'max' => $max,
                'name' => $checkClass->name($config),
            ]));

            $this->runCheck($checkClass, $config);

            $current++;
        }

        $this->output->writeln('');
    }

    /**
     * Draw message in a box.
     *
     * @param string $title
     */
    protected function drawBox(string $title)
    {
        $length = Str::length(strip_tags($title)) + 8;

        $this->output->writeln('|' . str_repeat('-', $length) . '|');
        $this->output->writeln('|    ' . $title . '    |');
        $this->output->writeln('|' . str_repeat('-', $length) . '|');

        $this->output->newLine();
    }

    /**
     * Make check run.
     *
     * @param Check $check
     * @param array $config
     */
    protected function runCheck(Check $check, array $config)
    {
        if ($check->check($config)) {
            $this->output->write('<fg=green>✔</fg=green>');
        } else {
            $this->output->write('<fg=red>✘</fg=red>');

            $this->messages[] = $check->message($config);
        }

        $this->output->write(PHP_EOL);
    }
}
