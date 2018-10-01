<?php

namespace BeyondCode\SelfDiagnosis\Tests\Checks;

use BeyondCode\SelfDiagnosis\Checks\StorageDirectoryIsLinked;
use Illuminate\Filesystem\Filesystem;
use Orchestra\Testbench\TestCase;

/**
 * @group checks
 */
class StorageDirectoryIsLinkedTest extends TestCase
{
    /** @test */
    public function it_checks_if_the_storage_directory_is_linked()
    {
        $filesystem = \Mockery::mock(Filesystem::class);

        $filesystem->shouldReceive('isDirectory')
            ->with(public_path('storage'))
            ->andReturn(true);

        $check = new StorageDirectoryIsLinked($filesystem);

        $this->assertTrue($check->check([]));
    }

    /**
     * @test
     */
    public function it_checks_if_the_storage_directory_isnt_linked()
    {
        $filesystem = \Mockery::mock(Filesystem::class);

        $filesystem->shouldReceive('isDirectory')
            ->with(public_path('storage'))
            ->andReturn(false);

        $check = new StorageDirectoryIsLinked($filesystem);

        $this->assertFalse($check->check([]));
    }

    /**
     * @test
     */
    public function it_failed_nice_if_the_storage_directory_check_failed()
    {
        $filesystem = \Mockery::mock(Filesystem::class);

        $filesystem->shouldReceive('isDirectory')
            ->with(public_path('storage'))
            ->andThrow(\Exception::class, 'testexception');

        $check = new StorageDirectoryIsLinked($filesystem);

        $this->assertFalse($check->check([]));
    }

    /**
     * @test
     */
    public function it_returns_a_name_for_the_check()
    {
        $check = app(StorageDirectoryIsLinked::class);
        $this->assertInternalType('string', $check->name([]));
    }

    /**
     * @test
     */
    public function it_returns_a_message_for_the_check()
    {
        $check = app(StorageDirectoryIsLinked::class);
        $this->assertInternalType('string', $check->message([]));
    }
}
