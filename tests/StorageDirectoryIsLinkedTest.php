<?php

namespace BeyondCode\SelfDiagnosis\Tests;

use BeyondCode\SelfDiagnosis\Checks\StorageDirectoryIsLinked;
use Illuminate\Filesystem\Filesystem;
use Orchestra\Testbench\TestCase;

class StorageDirectoryIsLinkedTest extends TestCase
{
    /** @test */
    public function it_checks_if_the_storage_directory_is_linked()
    {
        $filesystem = \Mockery::mock(Filesystem::class);

        $filesystem->shouldReceive('type')
            ->with(public_path('storage'))
            ->andReturn('link');

        $check = new StorageDirectoryIsLinked($filesystem);

        $this->assertTrue($check->check());
    }
}