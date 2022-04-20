<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File;

use finfo;
use InvalidArgumentException;
use Nonz250\Storage\App\Domain\File\Exceptions\MimeTypeException;
use Nonz250\Storage\App\Domain\File\ValueObject\FileIdentifier;
use Nonz250\Storage\App\Domain\File\ValueObject\FileName;
use Nonz250\Storage\App\Domain\File\ValueObject\Image;
use Nonz250\Storage\App\Domain\File\ValueObject\MimeType;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;

final class File
{
    private FileIdentifier $fileIdentifier;
    private ClientId $clientId;
    private FileName $fileName;
    private Image $image;
    private MimeType $thumbnailMimeType;

    public function __construct(FileIdentifier $fileIdentifier, ClientId $clientId, FileName $fileName, Image $image)
    {
        $this->fileIdentifier = $fileIdentifier;
        $this->clientId = $clientId;
        $this->fileName = $fileName;
        $this->image = $image;
        $this->thumbnailMimeType = $this->mimeType();
    }

    public function identifier(): FileIdentifier
    {
        return $this->fileIdentifier;
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
        $fileInfo = new finfo();
        $buffer = $fileInfo->buffer((string)$this->image(), FILEINFO_MIME_TYPE);

        if ($buffer === false) {
            throw new MimeTypeException('Failed to get mimetype.'); // @codeCoverageIgnore
        }

        try {
            $mimeType = new MimeType($buffer);
        } catch (InvalidArgumentException $e) {
            throw new MimeTypeException('Invalid mimetype.');
        }

        return $mimeType;
    }

    public function fileNameWithOriginExtension(): string
    {
        return $this->fileName() . $this->mimeType()->extension();
    }

    public function uniqueFileNameWithOriginExtension(): string
    {
        return $this->identifier() . $this->mimeType()->extension();
    }

    public function uniqueFileNameWithThumbnailExtension(): string
    {
        return $this->identifier() . $this->thumbnailMimeType()->extension();
    }

    public function thumbnailMimeType(): MimeType
    {
        return $this->thumbnailMimeType;
    }

    public function changeThumbnailMimeType(MimeType $mimeType): void
    {
        $this->thumbnailMimeType = $mimeType;
    }
}
