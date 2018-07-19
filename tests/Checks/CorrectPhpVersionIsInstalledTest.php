<?php

namespace BeyondCode\SelfDiagnosis\Tests\Checks;

use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\Checks\CorrectPhpVersionIsInstalled;

class CorrectPhpVersionIsInstalledTest extends TestCase
{
    /** @test */
    public function it_detects_the_php_version_from_composer_json()
    {
        $this->app->setBasePath(__DIR__ . '/../fixtures');

        $check = app(CorrectPhpVersionIsInstalled::class);

        $this->assertSame('7.1.3', $check->getRequiredPhpVersion());
    }
}
