<?php
declare(strict_types=1);

namespace Tests\Domain\User\ValueObject;

use InvalidArgumentException;
use Nonz250\Storage\App\Domain\User\ValueObject\UserName;
use PHPUnit\Framework\TestCase;
use Tests\StringTestHelper;

class UserNameTest extends TestCase
{
    public function test__construct(): UserName
    {
        $expected = StringTestHelper::randomByHex(UserName::LENGTH);
        $userName = new UserName($expected);
        $this->assertSame($expected, (string)$userName);
        return $userName;
    }

    public function testRequiredException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = '';
        new UserName($expected);
    }

    public function testLengthException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = StringTestHelper::randomByHex(UserName::LENGTH + 2);
        new UserName($expected);
    }

    public function testHexException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = StringTestHelper::random(UserName::LENGTH);
        new UserName($expected);
    }
}
