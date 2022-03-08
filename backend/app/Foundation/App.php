<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Foundation;

class App
{
    /**
     * @param string $environment
     * @return bool
     */
    public static function environment(string $environment): bool
    {
        return $_ENV['APP_ENV'] === $environment;
    }
}
