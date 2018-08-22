<?php

namespace BeyondCode\SelfDiagnosis\Tests\Checks;

use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\Composer;
use BeyondCode\SelfDiagnosis\Checks\ComposerWithDevDependenciesIsUpToDate;

class ComposerWithDevDependenciesIsUpToDateTest extends TestCase
{
    /**
     * @test
     */
    public function it_checks_that_composer_is_up_to_date()
    {
        $composer = \Mockery::mock(Composer::class);
        $composer->shouldReceive('setWorkingPath');
        $composer->shouldReceive('installDryRun')->once()->andReturn('Nothing to install or update');

        $check = new ComposerWithDevDependenciesIsUpToDate($composer);

        $this->assertTrue($check->check([]));
    }

    /**
     * @test
     */
    public function it_detects_that_composer_isnt_up_to_date()
    {
        $composer = \Mockery::mock(Composer::class);
        $composer->shouldReceive('setWorkingPath');
        $composer->shouldReceive('installDryRun')->once()->andReturn('Something to update');

        $check = new ComposerWithDevDependenciesIsUpToDate($composer);

        $this->assertFalse($check->check([]));
    }

    /**
     * @test
     */
    public function it_use_the_additional_options()
    {
        $additional = str_random();

        $composer = \Mockery::mock(Composer::class);
        $composer->shouldReceive('setWorkingPath');
        $composer->shouldReceive('installDryRun')->once()->withArgs(function ($arg) use ($additional) {
            return str_contains($arg, $additional);
        })->andReturn('Nothing to install or update');

        $check = new ComposerWithDevDependenciesIsUpToDate($composer);

        $this->assertTrue($check->check([
            'additional_options' => $additional,
        ]));
    }

    /**
     * @test
     */
    public function it_returns_a_name_for_the_check()
    {
        $check = app(ComposerWithDevDependenciesIsUpToDate::class);
        $this->assertInternalType('string', $check->name([]));
    }

    /**
     * @test
     */
    public function it_returns_a_message_for_the_check()
    {
        $check = app(ComposerWithDevDependenciesIsUpToDate::class);
        $this->assertInternalType('string', $check->message([]));
    }
}