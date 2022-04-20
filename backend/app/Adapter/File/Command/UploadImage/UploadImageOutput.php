<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Adapter\File\Command\UploadImage;

use Nonz250\Storage\App\Domain\File\Command\UploadImage\UploadImageOutputPort;
use Nonz250\Storage\App\Domain\File\ValueObject\FileIdentifier;

final class UploadImageOutput implements UploadImageOutputPort
{
    private FileIdentifier $identifier;
    private string $originFileName;
    private string $fileName;
    private string $originUrl;
    private string $thumbnailUrl;

    public function __construct(
        FileIdentifier $identifier,
        string $originFileName,
        string $fileName,
        string $originUrl,
        string $thumbnailUrl
    ) {
        $this->identifier = $identifier;
        $this->originFileName = $originFileName;
        $this->fileName = $fileName;
        $this->originUrl = $originUrl;
        $this->thumbnailUrl = $thumbnailUrl;
    }

    public function identifier(): FileIdentifier
    {
        return $this->identifier;
    }

    public function originFileName(): string
    {
        return $this->originFileName;
    }

    public function fileName(): string
    {
        return $this->fileName;
    }

    public function originUrl(): string
    {
        return $this->originUrl;
    }

    public function thumbnailUrl(): string
    {
        return $this->thumbnailUrl;
    }
}
