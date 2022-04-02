<?php
declare(strict_types=1);

namespace Tests\Domain\File\ValueObject;

use Nonz250\Storage\App\Domain\File\ValueObject\MimeType;
use PHPUnit\Framework\TestCase;

class MimeTypeTest extends TestCase
{
    public function testExtension(): void
    {
        $mimeType = new MimeType(MimeType::MIME_TYPE_BMP);
        $this->assertSame('.bmp', $mimeType->extension());

        $mimeType = new MimeType(MimeType::MIME_TYPE_GIF);
        $this->assertSame('.gif', $mimeType->extension());

        $mimeType = new MimeType(MimeType::MIME_TYPE_JPEG);
        $this->assertSame('.jpeg', $mimeType->extension());

        $mimeType = new MimeType(MimeType::MIME_TYPE_PNG);
        $this->assertSame('.png', $mimeType->extension());

        $mimeType = new MimeType(MimeType::MIME_TYPE_SVG);
        $this->assertSame('.svg', $mimeType->extension());

        $mimeType = new MimeType(MimeType::MIME_TYPE_WEBP);
        $this->assertSame('.webp', $mimeType->extension());
    }
}
