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
        $url = rtrim($this->config("url"), '/').$segments;
        Assert::notEmpty($url, "$this->env environment url is empty.");
        return $url;
    }

    public function hasBasicAuth(): bool
    {
        return $this->config("basic_auth.enabled") === true;
    }

    public function basicAuthUsername(): ?string
    {
        return $this->config("basic_auth.username");
    }

    public function basicAuthPassword(): ?string
    {
        return $this->config("basic_auth.password");
    }

    public function authKey(): ?string
    {
        return $this->config("auth_key");
    }

    public function filePaths(): array
    {
        return $this->config("file_paths", ['public', 'storage']);
    }

    public function excludedFilePaths(): array
    {
        return $this->config('excluded_file_paths', []);
    }

    private function config($key, $default = null)
    {
        return config("wire.environments.$this->env.$key", $default);
    }
}
