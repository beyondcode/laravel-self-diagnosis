<?php

namespace BeyondCode\SelfDiagnosis\Tests;

use Illuminate\Support\Facades\Artisan;
use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\Checks\HorizonIsRunning;

class HorizonIsRunningTest extends TestCase
{
    /** @test */
    public function it_succeeds_when_horizon_is_running()
    {
        $check = new HorizonIsRunning();

        Artisan::shouldReceive('call');

        Artisan::shouldReceive('output')
            ->andReturn('Horizon is running.');

        $this->assertTrue($check->check([]));
    }

    /** @test */
    public function is_fails_when_horizon_is_not_running()
    {
        $check = new HorizonIsRunning();

        Artisan::shouldReceive('call');

        Artisan::shouldReceive('output')
            ->andReturn('Horizon is paused.');

        $this->assertFalse($check->check([]));
    }
}
