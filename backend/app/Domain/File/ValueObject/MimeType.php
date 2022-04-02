<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File\ValueObject;

use Nonz250\Storage\App\Foundation\ValueObject\Enum;

final class MimeType extends Enum
{
    public const MIME_TYPE_BMP = 'image/bmp';
    public const MIME_TYPE_GIF = 'image/gif';
    public const MIME_TYPE_JPEG = 'image/jpeg';
    public const MIME_TYPE_PNG = 'image/png';
    public const MIME_TYPE_SVG = 'image/svg+xml';
    public const MIME_TYPE_WEBP = 'image/webp';

    public function extension(): string
    {
        $extensions = [
            self::MIME_TYPE_BMP => '.bmp',
            self::MIME_TYPE_GIF => '.gif',
            self::MIME_TYPE_JPEG => '.jpeg',
            self::MIME_TYPE_PNG => '.png',
            self::MIME_TYPE_SVG => '.svg',
            self::MIME_TYPE_WEBP => '.webp',
        ];
        return $extensions[(string)$this];
    }
}
