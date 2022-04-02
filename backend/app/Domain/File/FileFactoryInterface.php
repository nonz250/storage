<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File;

use Nonz250\Storage\App\Domain\File\ValueObject\FileName;
use Nonz250\Storage\App\Domain\File\ValueObject\Image;

interface FileFactoryInterface
{
    public function newImageFile(FileName $fileName, Image $image): File;
}
