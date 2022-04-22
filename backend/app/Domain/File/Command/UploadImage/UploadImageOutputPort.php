<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File\Command\UploadImage;

use Nonz250\Storage\App\Domain\File\ValueObject\FileIdentifier;

interface UploadImageOutputPort
{
    public function identifier(): FileIdentifier;

    public function originFileName(): string;

    public function fileName(): string;

    public function originPath(): string;

    public function thumbnailPath(): string;
}
