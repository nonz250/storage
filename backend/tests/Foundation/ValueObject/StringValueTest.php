<?php
declare(strict_types=1);

namespace Tests\Foundation\ValueObject;

use Nonz250\Storage\App\Foundation\ValueObject\StringValue;
use PHPUnit\Framework\TestCase;

final class StringValueTest extends TestCase
{
    public function test__construct(): void
    {
        $expected = '';
        $class = new class($expected) extends StringValue {
            public function __construct(string $value)
            {
                $this->value = $value;
            }

            protected function validate(string $value): void
            {
            }
        };
        $this->assertSame($expected, (string)$class);
        $this->assertTrue($class->isEmpty());
        $this->assertInstanceOf(StringValue::class, $class);
    }
}
