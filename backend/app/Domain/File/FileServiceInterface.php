<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File;

use Nonz250\Storage\App\Domain\File\Exceptions\UploadFileException;

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
     * @throws UploadFileException
     * @return string
     */
    public function uploadThumbnailImage(File $file, int $resizeWidth): string;
}
