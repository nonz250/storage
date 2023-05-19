<?php
declare(strict_types=1);

namespace Tests\Foundation\Identity;

use InvalidArgumentException;
use Nonz250\Storage\App\Foundation\Identity\Identifier;
use Nonz250\Storage\App\Foundation\Identity\StringIdentifier;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;
use Tests\StringTestHelper;

final class StringIdentifierTest extends TestCase
{
    public function test__construct(): void
    {
        $expected = Ulid::generate();
        $identifier = new class($expected) implements Identifier {
            use StringIdentifier;

            public function __construct(string $identifier)
            {
                $this->identifier = $identifier;
            }
        };
        $this->assertObjectHasProperty('identifier', $identifier);
        $this->assertSame($expected, (string)$identifier);
        $this->assertTrue($identifier->equals(clone $identifier));
    }

    public function testNotEqual(): void
    {
        $expected = Ulid::generate();
        $identifier = new class($expected) implements Identifier {
            use StringIdentifier;

            public function __construct(string $identifier)
            {
                $this->identifier = $identifier;
            }
        };
        $expected = Ulid::generate();
        $another = new class($expected) implements Identifier {
            use StringIdentifier;

            public function __construct(string $identifier)
            {
                $this->validate($identifier);
                $this->identifier = $identifier;
            }

            private function validate(string $identifier): void
            {
                if (!$this->isValidForUlid($identifier)) {
                    throw new InvalidArgumentException();
                }
            }
        };
        $this->assertFalse($another->equals($identifier));
    }

    public function testLength(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = StringTestHelper::random();
        new class($expected) implements Identifier {
            use StringIdentifier;

            public function __construct(string $identifier)
            {
                $this->validate($identifier);
                $this->identifier = $identifier;
            }

            private function validate(string $identifier): void
            {
                if (!$this->isValidForUlid($identifier)) {
                    throw new InvalidArgumentException();
                }
            }
        };
    }

    public function testIsValidForUlid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = StringTestHelper::randomByMb4(26);
        new class($expected) implements Identifier {
            use StringIdentifier;

            public function __construct(string $identifier)
            {
                $this->validate($identifier);
                $this->identifier = $identifier;
            }

            private function validate(string $identifier): void
            {
                if (!$this->isValidForUlid($identifier)) {
                    throw new InvalidArgumentException();
                }
            }
        };
    }
}
