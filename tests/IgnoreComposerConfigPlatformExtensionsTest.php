<?php
    
    namespace Tests\Unit;

    use Orchestra\Testbench\TestCase;
    
    class IgnoreComposerConfigPlatformExtensionsTest extends TestCase
    {
        /** @test */
        public function it_checks_if_config_platform_extensions_are_ignored()
        {
            $check = app(\BeyondCode\SelfDiagnosis\Checks\PhpExtensionsAreInstalled::class);
            $this->assertFalse($check->check([
                'include_composer_extensions' => true,
                'ignore_composer_config_platform_extensions' => false,
            ]));
            $this->assertTrue($check->check([
                'include_composer_extensions' => true,
                'ignore_composer_config_platform_extensions' => true,
            ]));
        }
    }
