<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File\Command\UploadImage;

use Nonz250\Storage\App\Domain\File\ValueObject\FileName;
use Nonz250\Storage\App\Domain\File\ValueObject\Image;
use Nonz250\Storage\App\Domain\File\ValueObject\MimeType;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;

interface UploadImageInputPort
{
    public function clientId(): ClientId;

    public function fileName(): FileName;

    public function image(): Image;

    public function mimeType(): MimeType;
}
