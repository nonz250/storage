<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File\Command\UploadImage;

use Nonz250\Storage\App\Domain\File\ValueObject\FileName;
use Nonz250\Storage\App\Domain\File\ValueObject\Image;

interface UploadImageInputPort
{
    public function fileName(): FileName;

    public function image(): Image;
}
