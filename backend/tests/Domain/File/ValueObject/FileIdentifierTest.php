<?php
declare(strict_types=1);

namespace Tests\Domain\File\ValueObject;

use InvalidArgumentException;
use Nonz250\Storage\App\Domain\File\ValueObject\FileIdentifier;
use Nonz250\Storage\App\Foundation\Identity\Identifier;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;
use Tests\StringTestHelper;

final class FileIdentifierTest extends TestCase
{
    public function test__construct(): Identifier
    {
        $expected = Ulid::generate();
        $fileIdentifier = new FileIdentifier($expected);
        $this->assertInstanceOf(Identifier::class, $fileIdentifier);
        $this->assertSame($expected, (string)$fileIdentifier);
        return $fileIdentifier;
    }

    public function testIsValidForUlid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = StringTestHelper::random();
        new FileIdentifier($expected);
    }
}
