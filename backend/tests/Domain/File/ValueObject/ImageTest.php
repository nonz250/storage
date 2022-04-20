<?php
declare(strict_types=1);

namespace Tests\Domain\File\ValueObject;

use InvalidArgumentException;
use Nonz250\Storage\App\Domain\File\ValueObject\FileString;
use PHPUnit\Framework\TestCase;
use Tests\StringTestHelper;

class ImageTest extends TestCase
{
    public function test__construct(): FileString
    {
        $expected = StringTestHelper::randomFast(FileString::MAX_LENGTH);
        $fileString = new FileString($expected);
        $this->assertSame($expected, (string)$fileString);
        return $fileString;
    }

    public function testRequiredException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = '';
        new FileString($expected);
    }

    public function testMaxLengthException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = StringTestHelper::randomFast(FileString::MAX_LENGTH + 1);
        new FileString($expected);
    }
}
