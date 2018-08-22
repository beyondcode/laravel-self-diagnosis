<?php

namespace BeyondCode\SelfDiagnosis\Tests\Checks;

use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\Checks\PhpExtensionsAreDisabled;

class PhpExtensionsAreDisabledTest extends TestCase
{

    /**
     * @test
     */
    public function it_detects_that_an_extension_is_loaded()
    {
        $check = app(PhpExtensionsAreDisabled::class);
        $this->assertFalse($check->check([
            'extensions' => [
                'pcre', //pcre is required by mockery that we require in dev
            ],
        ]));
    }

    /**
     * @test
     */
    public function it_detects_that_an_extension_isnt_loaded()
    {
        $check = app(PhpExtensionsAreDisabled::class);
        $this->assertTrue($check->check([
            'extensions' => [
                'notexisting',
            ],
        ]));
    }

    /**
     * @test
     */
    public function it_returns_a_name_for_the_check()
    {
        $check = app(PhpExtensionsAreDisabled::class);
        $this->assertInternalType('string', $check->name([]));
    }

    /**
     * @test
     */
    public function it_returns_a_message_for_the_check()
    {
        $check = app(PhpExtensionsAreDisabled::class);
        $this->assertInternalType('string', $check->message([]));
    }
}
