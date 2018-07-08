<?php

namespace BeyondCode\SelfDiagnosis\Tests;

use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\Checks\AppKeyIsSet;

class AppKeyIsSetTest extends TestCase
{
    /** @test */
    public function it_checks_app_key_existance()
    {
        $check = app(AppKeyIsSet::class);
        $this->assertFalse($check->check([]));

        $this->app['config']->set('app.key', 'foo');

        $this->assertTrue($check->check([]));
    }
}
