<?php

namespace BeyondCode\SelfDiagnosis\Tests\Checks;

use Illuminate\Support\Facades\Artisan;
use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\Checks\MigrationsAreUpToDate;

/**
 * @group checks
 */
class MigrationsAreUpToDateTest extends TestCase
{
    /** @test */
    public function it_detects_that_migrations_are_up_to_date()
    {
        $check = new MigrationsAreUpToDate();

        Artisan::shouldReceive('call');

        Artisan::shouldReceive('output')
            ->andReturn('Nothing to migrate.');

        $this->assertTrue($check->check([]));
    }
    /** @test */
    public function it_detects_that_migrations_need_to_run()
    {
        $check = new MigrationsAreUpToDate();

        Artisan::shouldReceive('call');

        Artisan::shouldReceive('output')
            ->andReturn('CREATE TABLE foo');

        $this->assertFalse($check->check([]));
    }

    /**
     * @test
     */
    public function it_returns_a_name_for_the_check()
    {
        $check = app(MigrationsAreUpToDate::class);
        $this->assertInternalType('string', $check->name([]));
    }

    /**
     * @test
     */
    public function it_returns_a_message_for_the_check()
    {
        $check = app(MigrationsAreUpToDate::class);
        $this->assertInternalType('string', $check->message([]));
    }

    /**
     * @test
     *
     * @depends it_returns_a_message_for_the_check
     */
    public function it_detects_that_migrations_check_failed()
    {
        $check = new MigrationsAreUpToDate();

        $firstMessage = $check->message([]);

        Artisan::shouldReceive('call')->andThrow(\PDOException::class, 'testmessage');

        $this->assertFalse($check->check([]));

        $message = $check->message([]);
        $this->assertNotEquals($message, $firstMessage);
    }
}
