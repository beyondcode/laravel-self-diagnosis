<?php

namespace BeyondCode\SelfDiagnosis\Tests\Checks;

use Illuminate\Support\Facades\Artisan;
use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\Checks\MigrationsAreUpToDate;

class MigrationsAreUpToDateTest extends TestCase
{
    /** @test */
    public function it_detects_that_migrations_are_up_to_date()
    {
        $check = new MigrationsAreUpToDate();

        Artisan::shouldReceive('call');

        Artisan::shouldReceive('output')
            ->andReturn('Nothing to migrate.');

        $this->assertTrue($check->check([]));
    }
    /** @test */
    public function it_detects_that_migrations_need_to_run()
    {
        $check = new MigrationsAreUpToDate();

        Artisan::shouldReceive('call');

        Artisan::shouldReceive('output')
            ->andReturn('CREATE TABLE foo');

        $this->assertFalse($check->check([]));
    }
}
