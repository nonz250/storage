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
    private string $originPath;
    private string $thumbnailPath;

    public function __construct(
        FileIdentifier $identifier,
        string $originFileName,
        string $fileName,
        string $originPath,
        string $thumbnailPath
    ) {
        $this->identifier = $identifier;
        $this->originFileName = $originFileName;
        $this->fileName = $fileName;
        $this->originPath = $originPath;
        $this->thumbnailPath = $thumbnailPath;
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

    public function originPath(): string
    {
        return $this->originPath;
    }

    public function thumbnailPath(): string
    {
        return $this->thumbnailPath;
    }
}
