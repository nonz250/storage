<?php
declare(strict_types=1);

namespace Tests\Foundation\ValueObject;

use InvalidArgumentException;
use Nonz250\Storage\App\Foundation\ValueObject\EmailValue;
use PHPUnit\Framework\TestCase;
use Tests\StringTestHelper;

class EmailValueTest extends TestCase
{
    public function test__construct(): void
    {
        $expected = StringTestHelper::randomEmail();
        $class = $this->generateEmailValue($expected);
        $this->assertSame($expected, (string)$class);
    }

    public function testFormatException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = StringTestHelper::random();
        $this->generateEmailValue($expected);
    }

    private function generateEmailValue(string $value): EmailValue
    {
        return new class($value) extends EmailValue {
            public function __construct(string $value)
            {
                $this->validate($value);
                $this->value = $value;
            }
        };
    }
}
