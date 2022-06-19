<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Foundation;

final class App
{
    /**
     * @param string $environment
     *
     * @return bool
     */
    public static function environment(string $environment): bool
    {
        return $_ENV['APP_ENV'] === $environment;
    }

    public static function env(string $key, $default = '')
    {
        return $_ENV[$key] ?? $default;
    }

    public static function migrationTable(): string
    {
        return self::env('MIGRATIONS_TABLE', 'migrations');
    }
}
