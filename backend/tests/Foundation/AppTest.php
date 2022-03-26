<?php
declare(strict_types=1);

namespace Tests\Foundation;

use Nonz250\Storage\App\Foundation\App;
use Nonz250\Storage\App\Shared\ValueObject\Environment;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    public function testEnvironment(): void
    {
        $this->assertFalse(App::environment(Environment::LOCAL));
        $this->assertTrue(App::environment(Environment::TESTING));
    }

    public function testEnv(): void
    {
        $this->assertSame((string)$_ENV['APP_ENV'], App::env('APP_ENV'));
    }
}
