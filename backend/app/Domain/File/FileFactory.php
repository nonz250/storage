<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File;

use Nonz250\Storage\App\Domain\File\ValueObject\FileIdentifier;
use Nonz250\Storage\App\Domain\File\ValueObject\FileName;
use Nonz250\Storage\App\Domain\File\ValueObject\Image;
use Symfony\Component\Uid\Ulid;

final class FileFactory implements FileFactoryInterface
{
    public function newImageFile(FileName $fileName, Image $image): File
    {
        return new File(new FileIdentifier(Ulid::generate()), $fileName, $image);
    }
}
