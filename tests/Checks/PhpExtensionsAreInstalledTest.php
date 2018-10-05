<?php

namespace BeyondCode\SelfDiagnosis\Tests\Checks;

use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\Checks\PhpExtensionsAreInstalled;

/**
 * @group checks
 */
class PhpExtensionsAreInstalledTest extends TestCase
{

    /**
     * @test
     */
    public function it_will_succeed_if_we_dont_require_any_extension()
    {
        $check = app(PhpExtensionsAreInstalled::class);
        $this->assertTrue($check->check([]), 'The check should pass if we didn\'t require any extension but it failed');
    }

    /**
     * @test
     */
    public function it_will_succeed_if_we_have_the_extensions()
    {
        $check = app(PhpExtensionsAreInstalled::class);
        $this->assertTrue($check->check([
            'extensions' => get_loaded_extensions(),
        ]), 'The check should pass if we only require extension that we have');
    }

    /**
     * @test
     */
    public function it_will_not_succeed_if_we_have_not_all_the_extensions()
    {
        $extensions = get_loaded_extensions();
        $extensions[] = 'not_existing_extension';
        $check = app(PhpExtensionsAreInstalled::class);
        $this->assertfalse($check->check([
            'extensions' => $extensions,
        ]), 'The check should fail if we require any extension that we didn\'t have but it succeed');
    }

    /**
     * @test
     */
    public function it_returns_a_name_for_the_check()
    {
        $check = app(PhpExtensionsAreInstalled::class);
        $this->assertInternalType('string', $check->name([]));
    }

    /**
     * @test
     */
    public function it_returns_a_message_for_the_check()
    {
        $check = app(PhpExtensionsAreInstalled::class);
        $this->assertInternalType('string', $check->message([]));
    }
}
