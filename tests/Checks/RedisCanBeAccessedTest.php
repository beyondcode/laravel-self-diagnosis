<?php

namespace BeyondCode\SelfDiagnosis\Tests\Checks;

use BeyondCode\SelfDiagnosis\SelfDiagnosisServiceProvider;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;
use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\Checks\RedisCanBeAccessed;
use PHPUnit\Framework\MockObject\MockObject;

class RedisCanBeAccessedTest extends TestCase
{
    public function getPackageProviders($app)
    {
        return [
            SelfDiagnosisServiceProvider::class,
        ];
    }

    /** @test */
    public function it_succeeds_when_default_connection_works()
    {
        $check = app(RedisCanBeAccessed::class);
        $this->assertFalse($check->check([]));

        /** @var MockObject|Connection $connectionMock */
        $connectionMock = $this->getMockBuilder(Connection::class)
            ->setMethods(['connect', 'isConnected', 'createSubscription']) // we have to declare the abstract method createSubscription
            ->getMock();
        $connectionMock->expects($this->once())
            ->method('connect');
        $connectionMock->expects($this->once())
            ->method('isConnected')
            ->willReturn(true);

        Redis::shouldReceive('connection')
            ->with(null)
            ->andReturn($connectionMock);
        $this->assertTrue($check->check([]));
    }

    /** @test */
    public function it_succeeds_when_named_connections_work()
    {
        $config = [
            'default_connection' => false,
            'connections' => [
                'some_connection',
            ],
        ];

        $check = app(RedisCanBeAccessed::class);
        $this->assertFalse($check->check($config));

        /** @var MockObject|Connection $connectionMock */
        $connectionMock = $this->getMockBuilder(Connection::class)
            ->setMethods(['connect', 'isConnected', 'createSubscription']) // we have to declare the abstract method createSubscription
            ->getMock();
        $connectionMock->expects($this->once())
            ->method('connect');
        $connectionMock->expects($this->once())
            ->method('isConnected')
            ->willReturn(true);

        Redis::shouldReceive('connection')
            ->with('some_connection')
            ->andReturn($connectionMock);
        $this->assertTrue($check->check($config));
    }

    /** @test */
    public function it_fails_when_default_connection_does_not_work()
    {
        $check = app(RedisCanBeAccessed::class);
        $this->assertFalse($check->check([]));

        /** @var MockObject|Connection $connectionMock */
        $connectionMock = $this->getMockBuilder(Connection::class)
            ->setMethods(['connect', 'isConnected', 'createSubscription']) // we have to declare the abstract method createSubscription
            ->getMock();
        $connectionMock->expects($this->once())
            ->method('connect');
        $connectionMock->expects($this->once())
            ->method('isConnected')
            ->willReturn(false);

        Redis::shouldReceive('connection')
            ->with(null)
            ->andReturn($connectionMock);
        $this->assertFalse($check->check([]));
        $this->assertSame('The Redis cache can not be accessed: The default cache is not reachable.', $check->message([]));
    }

    /** @test */
    public function it_fails_when_named_connection_does_not_exist()
    {
        $config = [
            'default_connection' => false,
            'connections' => [
                'some_connection',
            ],
        ];

        $check = app(RedisCanBeAccessed::class);
        $this->assertFalse($check->check($config));

        /** @var MockObject|Connection $connectionMock */
        $connectionMock = $this->getMockBuilder(Connection::class)
            ->setMethods(['connect', 'isConnected', 'createSubscription']) // we have to declare the abstract method createSubscription
            ->getMock();
        $connectionMock->expects($this->once())
            ->method('connect');
        $connectionMock->expects($this->once())
            ->method('isConnected')
            ->willReturn(false);

        Redis::shouldReceive('connection')
            ->with('some_connection')
            ->andReturn($connectionMock);
        $this->assertFalse($check->check($config));
        $this->assertSame('The Redis cache can not be accessed: The named cache some_connection is not reachable.', $check->message($config));
    }
}
