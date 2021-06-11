<?php

namespace BeyondCode\SelfDiagnosis\Tests;

use BeyondCode\SelfDiagnosis\Checks\ServersArePingable;
use BeyondCode\SelfDiagnosis\Exceptions\InvalidConfigurationException;
use BeyondCode\SelfDiagnosis\SelfDiagnosisServiceProvider;
use Orchestra\Testbench\TestCase;

class ServersArePingableTest extends TestCase
{
    public function getPackageProviders($app)
    {
        return [
            SelfDiagnosisServiceProvider::class,
        ];
    }

    /** @test */
    public function it_succeeds_when_no_servers_are_given()
    {
        $check = new ServersArePingable();
        $this->assertTrue($check->check([]));
    }

    /** @test */
    public function it_fails_when_numeric_server_host_is_given()
    {
        $config = ['servers' => [1234567890]];

        $check = new ServersArePingable();

        $this->expectException(InvalidConfigurationException::class);
        $check->check($config);
    }

    /** @test */
    public function it_fails_when_no_host_key_is_given_for_server_in_array_form()
    {
        $config = ['servers' => [['port' => 80]]];

        $check = new ServersArePingable();

        $this->expectException(InvalidConfigurationException::class);
        $check->check($config);
    }

    /** @test */
    public function it_fails_when_not_supported_keys_are_given_for_server_in_array_form()
    {
        $config = ['servers' => [['host' => 'localhost', 'unsupported_param' => true]]];

        $check = new ServersArePingable();

        $this->expectException(InvalidConfigurationException::class);
        $check->check($config);
    }

    /** @test */
    public function it_fails_when_the_server_ip_cannot_be_resolved()
    {
        $config = ['servers' => [['host' => 'somethingthatdoesnotexist.local', 'timeout' => 1]]];

        $check = new ServersArePingable();
        $this->assertFalse($check->check($config));
        $this->assertSame("The server 'somethingthatdoesnotexist.local' (port: n/a) is not reachable (timeout after 1 seconds).", $check->message($config));
    }

    /** @test */
    public function it_fails_when_the_server_ip_cannot_be_reached()
    {
        $config = ['servers' => [['host' => '254.254.254.254', 'timeout' => 1]]];

        $check = new ServersArePingable();
        $this->assertFalse($check->check($config));
        $this->assertSame("The server '254.254.254.254' (port: n/a) is not reachable (timeout after 1 seconds).", $check->message($config));
    }

    /** @test */
    public function it_succeeds_when_the_server_ip_can_be_reached()
    {
        $config = ['servers' => [['host' => 'www.github.com', 'timeout' => 10]]];

        $check = new ServersArePingable();
        $this->assertTrue($check->check($config));
    }
}
