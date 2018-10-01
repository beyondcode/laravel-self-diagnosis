<?php

namespace BeyondCode\SelfDiagnosis\Tests\Checks;

use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\Checks\AppKeyIsSet;

/**
 * @group checks
 */
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

    /**
     * @test
     */
    public function it_returns_a_name_for_the_check()
    {
        $check = app(AppKeyIsSet::class);
        $this->assertInternalType('string', $check->name([]));
    }

    /**
     * @test
     */
    public function it_returns_a_message_for_the_check()
    {
        $check = app(AppKeyIsSet::class);
        $this->assertInternalType('string', $check->message([]));
    }
}
