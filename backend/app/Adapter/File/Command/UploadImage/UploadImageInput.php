<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Adapter\File\Command\UploadImage;

use Nonz250\Storage\App\Domain\File\Command\UploadImage\UploadImageInputPort;
use Nonz250\Storage\App\Domain\File\ValueObject\FileName;
use Nonz250\Storage\App\Domain\File\ValueObject\Image;
use Nonz250\Storage\App\Domain\File\ValueObject\MimeType;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;

final class UploadImageInput implements UploadImageInputPort
{
    private ClientId $clientId;
    private FileName $fileName;
    private Image $image;
    private MimeType $mimeType;

    public function __construct(ClientId $clientId, FileName $fileName, Image $image, MimeType $mimeType)
    {
        $this->clientId = $clientId;
        $this->fileName = $fileName;
        $this->image = $image;
        $this->mimeType = $mimeType;
    }

    public function clientId(): ClientId
    {
        return $this->clientId;
    }

    public function fileName(): FileName
    {
        return $this->fileName;
    }

    public function image(): Image
    {
        return $this->image;
    }

    public function mimeType(): MimeType
    {
        return $this->mimeType;
    }
}
