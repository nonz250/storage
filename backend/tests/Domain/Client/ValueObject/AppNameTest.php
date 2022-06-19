<?php
declare(strict_types=1);

namespace Tests\Domain\Client\ValueObject;

use InvalidArgumentException;
use Nonz250\Storage\App\Domain\Client\ValueObject\AppName;
use Nonz250\Storage\App\Foundation\ValueObject\StringValue;
use PHPUnit\Framework\TestCase;
use Tests\StringTestHelper;

final class AppNameTest extends TestCase
{
    public function test__construct(): AppName
    {
        $expected = StringTestHelper::randomByMb4(AppName::MAX_LENGTH);
        $appName = new AppName($expected);
        $this->assertInstanceOf(StringValue::class, $appName);
        $this->assertSame($expected, (string)$appName);
        return $appName;
    }

    public function testRequiredException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = '';
        new AppName($expected);
    }

    public function testMaxLengthException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = StringTestHelper::randomByMb4(AppName::MAX_LENGTH + 1);
        new AppName($expected);
    }
}
