<?php
declare(strict_types=1);

namespace Tests\Foundation\ValueObject;

use InvalidArgumentException;
use Nonz250\Storage\App\Foundation\ValueObject\Enum;
use PHPUnit\Framework\TestCase;

final class EnumTest extends TestCase
{
    public function test__construct(): void
    {
        $enum = new class(1) extends Enum {
            public const TEST_INT = 1;
        };
        $this->assertSame(1, $enum->value());
        $this->assertSame(1, $enum->toInt());
        $this->assertSame('1', (string)$enum);
    }

    public function testInvalidException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $enum = new class('') extends Enum {
            public const TEST_INT = 1;
        };
    }
}
