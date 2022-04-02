<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Adapter\File\Command\UploadImage;

use Nonz250\Storage\App\Domain\File\Command\UploadImage\UploadImageInputPort;
use Nonz250\Storage\App\Domain\File\ValueObject\FileName;
use Nonz250\Storage\App\Domain\File\ValueObject\Image;

final class UploadImageInput implements UploadImageInputPort
{
    private FileName $fileName;
    private Image $image;

    public function __construct(FileName $fileName, Image $image)
    {
        $this->fileName = $fileName;
        $this->image = $image;
    }

    public function fileName(): FileName
    {
        return $this->fileName;
    }

    public function image(): Image
    {
        return $this->image;
    }
}
