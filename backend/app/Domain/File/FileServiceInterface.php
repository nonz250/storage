<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File;

use Nonz250\Storage\App\Domain\File\Exceptions\ImageNotExistsException;
use Nonz250\Storage\App\Domain\File\Exceptions\RemoveFileException;
use Nonz250\Storage\App\Domain\File\Exceptions\UploadFileException;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;

interface FileServiceInterface
{
    /**
     * @param File $file
     *
     * @throws UploadFileException
     *
     * @return string
     */
    public function uploadOriginImage(File $file): string;

    /**
     * @param File $file
     * @param int $resizeWidth
     *
     * @throws UploadFileException
     *
     * @return string
     */
    public function uploadThumbnailImage(File $file, int $resizeWidth): string;

    /**
     * @param ClientId $clientId
     *
     * @throws ImageNotExistsException
     *
     * @return File[]
     */
    public function getImagesByClientId(ClientId $clientId): array;

    /**
     * @param File $file
     *
     * @throws RemoveFileException
     *
     * @return void
     */
    public function removeImage(File $file): void;
}
