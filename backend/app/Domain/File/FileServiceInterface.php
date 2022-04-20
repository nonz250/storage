<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File;

use Nonz250\Storage\App\Domain\File\Exceptions\UploadFileException;
use Nonz250\Storage\App\Domain\File\ValueObject\MimeType;

interface FileServiceInterface
{
    /**
     * @param File $file
     * @throws UploadFileException
     * @return string
     */
    public function uploadOriginImage(File $file): string;

    /**
     * @param File $file
     * @param int $resizeWidth
     * @param MimeType|null $mimeType
     * @throws UploadFileException
     * @return string
     */
    public function uploadThumbnailImage(File $file, ?MimeType $mimeType, int $resizeWidth): string;
}
