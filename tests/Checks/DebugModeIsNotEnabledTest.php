<?php

namespace BeyondCode\SelfDiagnosis\Tests\Checks;

use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\Checks\DebugModeIsNotEnabled;

class DebugModeIsNotEnabledTest extends TestCase
{
    /**
     * The original value from the app.debug config
     *
     * @var boolean
     */
    protected $originalConfig;

    /**
     * Store the original config to set it back afterward
     *
     * {@inheritDoc}
     * @see \Orchestra\Testbench\TestCase::setUp()
     */
    protected function setUp()
    {
        parent::setUp();
        $this->originalConfig = config('app.debug');
    }

    /**
     * Set the config back to the originalvalue
     *
     * {@inheritDoc}
     * @see \Orchestra\Testbench\TestCase::tearDown()
     */
    protected function tearDown()
    {
        config(['app.debug' => $this->originalConfig]);
        parent::tearDown();
    }

    /**
     * @test
     */
    public function it_fails_if_debug_is_enabled()
    {
        config(['app.debug' => true]);
        $check = app(DebugModeIsNotEnabled::class);
        $this->assertFalse($check->check([]), 'The check should fail because debug is enabled, but it didn\'t');
    }

    /**
     * @test
     */
    public function it_will_succes_if_debug_is_disabled()
    {
        config(['app.debug' => false]);
        $check = app(DebugModeIsNotEnabled::class);
        $this->assertTrue($check->check([]), 'The check should succeed because debug is disabled, but it didn\'t');
    }

    /**
     * @test
     */
    public function it_returns_a_name_for_the_check()
    {
        $check = app(DebugModeIsNotEnabled::class);
        $this->assertInternalType('string', $check->name([]));
    }

    /**
     * @test
     */
    public function it_returns_a_message_for_the_check()
    {
        $check = app(DebugModeIsNotEnabled::class);
        $this->assertInternalType('string', $check->message([]));
    }
}