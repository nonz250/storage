<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File\Command\UploadImage;

use Nonz250\Storage\App\Domain\File\Exceptions\UploadFileException;
use Nonz250\Storage\App\Domain\File\File;

interface UploadImageInterface
{
    /**
     * @param UploadImageInputPort $inputPort
     * @throws UploadFileException
     * @return File
     */
    public function process(UploadImageInputPort $inputPort): File;
}
