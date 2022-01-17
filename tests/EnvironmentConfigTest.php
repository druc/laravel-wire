<?php

namespace Druc\LaravelWire\Tests;

use Druc\LaravelWire\EnvironmentConfig;

class EnvironmentConfigTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'wire.environments' => [
                'stage' => [
                    'url' => 'https://testing.test',
                    'wire_key' => '12345',
                    'file_paths' => ['storage'],
                    'excluded_file_paths' => [],
                    'basic_auth' => [
                        'enabled' => true,
                        'username' => 'john',
                        'password' => 'doe'
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    public function get_environment_base_url(): void
    {
        $config = new EnvironmentConfig();

        $this->assertEquals('https://testing.test', $config->url());
    }

    /** @test */
    public function get_environment_url(): void
    {
        $config = new EnvironmentConfig();

        $this->assertEquals('https://testing.test/database', $config->url('/database'));
    }

    /** @test */
    public function has_basic_auth(): void
    {
        $config = new EnvironmentConfig();

        $this->assertTrue($config->hasBasicAuth());

        config([
            'wire.environments.stage.basic_auth.enabled' => false
        ]);

        $this->assertFalse($config->hasBasicAuth());
    }

    /** @test */
    public function get_basic_auth_username(): void
    {
        $config = new EnvironmentConfig();
        $this->assertEquals('john', $config->basicAuthUsername());
    }

    /** @test */
    public function get_basic_auth_password(): void
    {
        $config = new EnvironmentConfig();
        $this->assertEquals('doe', $config->basicAuthPassword());
    }

    /** @test */
    public function get_wire_key(): void
    {
        config([
            'wire.environments.stage.auth_key' => "12345"
        ]);
        $config = new EnvironmentConfig();
        $this->assertEquals('12345', $config->authKey());
    }

    /** @test */
    public function get_file_paths(): void
    {
        $config = new EnvironmentConfig();
        $this->assertEquals(['storage'], $config->filePaths());
    }

    /** @test */
    public function get_excluded_file_paths(): void
    {
        $config = new EnvironmentConfig();
        $this->assertEquals([], $config->excludedFilePaths());
    }
}
