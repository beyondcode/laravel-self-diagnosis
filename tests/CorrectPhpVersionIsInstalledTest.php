<?php

namespace BeyondCode\SelfDiagnosis\Tests;

use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\Checks\CorrectPhpVersionIsInstalled;
use Illuminate\Filesystem\Filesystem;

class CorrectPhpVersionIsInstalledTest extends TestCase
{
    /** @test */
    public function it_detects_the_php_constraint_from_composer_json()
    {
        $this->app->setBasePath(__DIR__ . '/fixtures');

        $check = app(CorrectPhpVersionIsInstalled::class);

        $this->assertSame('^7.1.3', $check->getRequiredPhpConstraint());
    }

    /** @test */
    public function it_detects_php_version_to_low()
    {

        $fileSystemMock = \Mockery::mock(Filesystem::class);

        $data = file_get_contents(__DIR__ . '/fixtures/composer.json');

        $data = str_replace('"php": "^7.1.3",', '"php": "^100",', $data);

        $fileSystemMock->shouldReceive('get')
            ->andReturn($data);

        $check = new CorrectPhpVersionIsInstalled($fileSystemMock);

        $this->assertFalse($check->check([]));
    }

    /** @test */
    public function it_detects_php_version_to_high()
    {
        $fileSystemMock = \Mockery::mock(Filesystem::class);

        $data = file_get_contents(__DIR__ . '/fixtures/composer.json');

        $data = str_replace('"php": "^7.1.3",', '"php": "<=1",', $data);

        $fileSystemMock->shouldReceive('get')
            ->andReturn($data);

        $check = new CorrectPhpVersionIsInstalled($fileSystemMock);

        $this->assertFalse($check->check([]));
    }

    /** @test */
    public function it_accepts_version_with_asterix()
    {
        $fileSystemMock = \Mockery::mock(Filesystem::class);

        $data = file_get_contents(__DIR__ . '/fixtures/composer.json');

        $data = str_replace('"php": "^7.1.3",', '"php": "7.*|8.*",', $data);

        $fileSystemMock->shouldReceive('get')
            ->andReturn($data);

        $check = new CorrectPhpVersionIsInstalled($fileSystemMock);

        $this->assertTrue($check->check([]));
    }
}
