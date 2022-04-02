<?php
declare(strict_types=1);

namespace Tests\Domain\File\ValueObject;

use InvalidArgumentException;
use Nonz250\Storage\App\Domain\File\ValueObject\Image;
use Nonz250\Storage\App\Domain\File\ValueObject\FileName;
use PHPUnit\Framework\TestCase;
use Tests\StringTestHelper;

class ImageTest extends TestCase
{
    public function test__construct(): Image
    {
        $expected = StringTestHelper::randomFast(Image::MAX_LENGTH);
        $image = new Image($expected);
        $this->assertSame($expected, (string)$image);
        return $image;
    }

    public function testRequiredException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = '';
        new Image($expected);
    }

    public function testMaxLengthException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $expected = StringTestHelper::randomFast(Image::MAX_LENGTH + 1);
        new Image($expected);
    }
}
