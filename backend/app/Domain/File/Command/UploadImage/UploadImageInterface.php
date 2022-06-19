<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File\Command\UploadImage;

use Nonz250\Storage\App\Domain\File\Exceptions\UploadFileException;

interface UploadImageInterface
{
    /**
     * @param UploadImageInputPort $inputPort
     *
     * @throws UploadFileException
     *
     * @return UploadImageOutputPort
     */
    public function process(UploadImageInputPort $inputPort): UploadImageOutputPort;
}
