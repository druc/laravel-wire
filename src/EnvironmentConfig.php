<?php

namespace Druc\LaravelWire;

use Webmozart\Assert\Assert;

class EnvironmentConfig
{
    private ?string $env;

    public function __construct(string $env = null)
    {
        $this->env = $env ?? config('wire.default', 'stage');
    }

    public function url($segments = ''): string
    {
        $url = rtrim(config("wire.environments.$this->env.url"), '/') . $segments;
        Assert::notEmpty($url, "$this->env environment url is empty.");
        return $url;
    }

    public function hasBasicAuth(): bool
    {
        return config("wire.environments.$this->env.basic_auth.enabled") === true;
    }

    public function basicAuthUsername(): string
    {
        Assert::keyExists(config("wire.environments.$this->env.basic_auth"), 'username');
        return config("wire.environments.$this->env.basic_auth.username");
    }

    public function basicAuthPassword(): string
    {
        Assert::keyExists(config("wire.environments.$this->env.basic_auth"), 'password');
        return config("wire.environments.$this->env.basic_auth.password");
    }

    public function authKey(): string
    {
        return config("wire.environments.$this->env.auth_key");
    }

    public function filePaths(): array
    {
        return config("wire.environments.$this->env.file_paths", ['public', 'storage']);
    }

    public function excludedFilePaths(): array
    {
        return config("wire.environments.$this->env.excluded_file_paths", []);
    }
}
