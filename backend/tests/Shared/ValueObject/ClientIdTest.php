<?php
declare(strict_types=1);

namespace Tests\Shared\ValueObject;

use InvalidArgumentException;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;
use PHPUnit\Framework\TestCase;
use Tests\StringTestHelper;

final class ClientIdTest extends TestCase
{
    public function test__construct(): ClientId
    {
        $expected = StringTestHelper::randomByHex(ClientId::LENGTH);
        $clientId = new ClientId($expected);
        $this->assertSame($expected, (string)$clientId);
        return $clientId;
    }

    public function testGenerate(): void
    {
        $clientId = ClientId::generate();
        $this->assertSame(ClientId::LENGTH, $clientId->count());
    }

    public function testRequiredException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = '';
        new ClientId($expected);
    }

    public function testLengthException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = StringTestHelper::randomByHex(ClientId::LENGTH + 2);
        new ClientId($expected);
    }

    public function testHexException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = StringTestHelper::random(ClientId::LENGTH);
        new ClientId($expected);
    }
}
