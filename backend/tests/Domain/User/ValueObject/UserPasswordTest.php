<?php
declare(strict_types=1);

namespace Tests\Domain\User\ValueObject;

use InvalidArgumentException;
use Nonz250\Storage\App\Domain\User\ValueObject\UserPassword;
use PHPUnit\Framework\TestCase;
use Tests\StringTestHelper;

class UserPasswordTest extends TestCase
{
    public function test__construct(): UserPassword
    {
        $expected = StringTestHelper::randomByHex(UserPassword::LENGTH);
        $userPassword = new UserPassword($expected);
        $this->assertSame($expected, (string)$userPassword);
        return $userPassword;
    }

    public function testRequiredException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = '';
        new UserPassword($expected);
    }

    public function testLengthException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = StringTestHelper::randomByHex(UserPassword::LENGTH + 2);
        new UserPassword($expected);
    }

    public function testPrintableException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = StringTestHelper::random(UserPassword::LENGTH, ["\n"]);
        new UserPassword($expected);
    }
}
