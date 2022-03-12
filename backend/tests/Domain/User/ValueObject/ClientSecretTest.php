<?php
declare(strict_types=1);

namespace Tests\Domain\User\ValueObject;

use InvalidArgumentException;
use Nonz250\Storage\App\Domain\User\ValueObject\ClientSecret;
use PHPUnit\Framework\TestCase;
use Tests\StringTestHelper;

class ClientSecretTest extends TestCase
{
    public function test__construct(): ClientSecret
    {
        $expected = StringTestHelper::randomByHex(ClientSecret::LENGTH);
        $clientSecret = new ClientSecret($expected);
        $this->assertSame($expected, (string)$clientSecret);
        return $clientSecret;
    }

    public function testRequiredException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = '';
        new ClientSecret($expected);
    }

    public function testLengthException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = StringTestHelper::randomByHex(ClientSecret::LENGTH + 2);
        new ClientSecret($expected);
    }

    public function testPrintableException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = StringTestHelper::random(ClientSecret::LENGTH, ["\n"]);
        new ClientSecret($expected);
    }
}
