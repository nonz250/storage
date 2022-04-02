<?php
declare(strict_types=1);

namespace Tests\Domain\Client\ValueObject;

use InvalidArgumentException;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientEmail;
use PHPUnit\Framework\TestCase;
use Tests\StringTestHelper;

class ClientEmailTest extends TestCase
{
    public function test__construct(): void
    {
        $expected = StringTestHelper::randomEmail();
        $clientEmail = new ClientEmail($expected);
        $this->assertSame($expected, (string)$clientEmail);
    }

    public function testRequiredException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = '';
        new ClientEmail($expected);
    }

    public function testFormatException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = StringTestHelper::random();
        new ClientEmail($expected);
    }
}
