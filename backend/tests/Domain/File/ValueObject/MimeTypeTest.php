<?php
declare(strict_types=1);

namespace Tests\Domain\File\ValueObject;

use Nonz250\Storage\App\Domain\File\ValueObject\MimeType;
use PHPUnit\Framework\TestCase;

final class MimeTypeTest extends TestCase
{
    public function testExtension(): void
    {
        $mimeType = new MimeType(MimeType::MIME_TYPE_BMP);
        $this->assertSame('.bmp', $mimeType->extension());
        $this->assertTrue($mimeType->isBmp());
        $this->assertFalse($mimeType->isGif());
        $this->assertFalse($mimeType->isJpeg());
        $this->assertFalse($mimeType->isPng());
        $this->assertFalse($mimeType->isWebp());

        $mimeType = new MimeType(MimeType::MIME_TYPE_GIF);
        $this->assertSame('.gif', $mimeType->extension());
        $this->assertFalse($mimeType->isBmp());
        $this->assertTrue($mimeType->isGif());
        $this->assertFalse($mimeType->isJpeg());
        $this->assertFalse($mimeType->isPng());
        $this->assertFalse($mimeType->isWebp());

        $mimeType = new MimeType(MimeType::MIME_TYPE_JPEG);
        $this->assertSame('.jpeg', $mimeType->extension());
        $this->assertFalse($mimeType->isBmp());
        $this->assertFalse($mimeType->isGif());
        $this->assertTrue($mimeType->isJpeg());
        $this->assertFalse($mimeType->isPng());
        $this->assertFalse($mimeType->isWebp());

        $mimeType = new MimeType(MimeType::MIME_TYPE_PNG);
        $this->assertSame('.png', $mimeType->extension());
        $this->assertFalse($mimeType->isBmp());
        $this->assertFalse($mimeType->isGif());
        $this->assertFalse($mimeType->isJpeg());
        $this->assertTrue($mimeType->isPng());
        $this->assertFalse($mimeType->isWebp());

        $mimeType = new MimeType(MimeType::MIME_TYPE_WEBP);
        $this->assertSame('.webp', $mimeType->extension());
        $this->assertFalse($mimeType->isBmp());
        $this->assertFalse($mimeType->isGif());
        $this->assertFalse($mimeType->isJpeg());
        $this->assertFalse($mimeType->isPng());
        $this->assertTrue($mimeType->isWebp());
    }
}
