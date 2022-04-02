<?php
declare(strict_types=1);

namespace Tests\Domain\File\ValueObject;

use InvalidArgumentException;
use Nonz250\Storage\App\Domain\File\ValueObject\FileName;
use PHPUnit\Framework\TestCase;
use Tests\StringTestHelper;

class FileNameTest extends TestCase
{
    public function test__construct(): FileName
    {
        $expected = StringTestHelper::randomByMb4();
        $fileName = new FileName($expected);
        $this->assertSame($expected, (string)$fileName);
        return $fileName;
    }

    public function testRequiredException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = '';
        new FileName($expected);
    }

    public function testMaxLengthException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = StringTestHelper::random(FileName::MAX_LENGTH + 1);
        new FileName($expected);
    }
}
