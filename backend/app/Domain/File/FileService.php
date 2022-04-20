<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File;

use LogicException;
use Nonz250\Storage\App\Domain\File\Exceptions\UploadFileException;
use Psr\Log\LoggerInterface;
use RuntimeException;

class FileService implements FileServiceInterface
{
    private const UPLOAD_DIRECTORY = 'storage';
    private const FULL_HD_WIDTH = 1920;

    public const UPLOAD_ORIGIN_DIRECTORY = DIRECTORY_SEPARATOR . self::UPLOAD_DIRECTORY . DIRECTORY_SEPARATOR . 'origin';
    public const UPLOAD_THUMBNAIL_DIRECTORY = DIRECTORY_SEPARATOR . self::UPLOAD_DIRECTORY . DIRECTORY_SEPARATOR . 'thumbnail';

    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function uploadOriginImage(File $file): string
    {
        $uploadStorageDirectory = getcwd() . self::UPLOAD_ORIGIN_DIRECTORY;
        $this->createDir($uploadStorageDirectory);

        $originFilePath = $uploadStorageDirectory . DIRECTORY_SEPARATOR . $file->uniqueFileNameWithOriginExtension();
        $byte = file_put_contents($originFilePath, (string)$file->fileString());
        if ($byte === false) {
            throw new UploadFileException('Failed to upload file.');
        }

        $this->logger->debug(sprintf('%s is %s bytes.', $file->fileNameWithOriginExtension(), $byte));

        return $originFilePath;
    }

    public function uploadThumbnailImage(File $file, int $resizeWidth = self::FULL_HD_WIDTH / 2): string
    {
        $uploadThumbnailDirectory = getcwd() . self::UPLOAD_THUMBNAIL_DIRECTORY;
        $this->createDir($uploadThumbnailDirectory);

        [$originWidth, $originHeight, $type] = getimagesizefromstring((string)$file->fileString());
        $this->logger->debug(sprintf('width: %s, height: %s, type: %s -- %s', $originWidth, $originHeight, $type, $file->identifier()));

        $source = imagecreatefromstring((string)$file->fileString());
        if ($source === false) {
            throw new UploadFileException('Failed to upload file.');
        }

        $ratio = $originWidth >= $resizeWidth ? $resizeWidth / $originWidth : 1;
        $newWidth = (int)($originWidth * $ratio);
        $newHeight = (int)($originHeight * $ratio);

        $thumbnail = imagecreatetruecolor($newWidth, $newHeight);
        if (!imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, $newWidth, $newHeight, $originWidth, $originHeight)) {
            throw new UploadFileException('Failed to upload file.');
        }

        $mimeType = $file->thumbnailMimeType();
        $thumbnailFilePath = $uploadThumbnailDirectory . DIRECTORY_SEPARATOR . $file->uniqueFileNameWithThumbnailExtension();
        if ($mimeType->isBmp()) {
            $result = imagebmp($thumbnail, $thumbnailFilePath);
        } elseif ($mimeType->isGif()) {
            $result = imagegif($thumbnail, $thumbnailFilePath);
        } elseif ($mimeType->isJpeg()) {
            $result = imagejpeg($thumbnail, $thumbnailFilePath);
        } elseif ($mimeType->isPng()) {
            $result = imagepng($thumbnail, $thumbnailFilePath);
        } elseif ($mimeType->isWebp()) {
            $result = imagewebp($thumbnail, $thumbnailFilePath);
        } else {
            $this->logger->error(sprintf('Unknown mimetype. [%s]', $mimeType));
            throw new LogicException('Unknown mimetype.');
        }

        if (!$result) {
            throw new UploadFileException('Failed to upload file.');
        }

        imagedestroy($thumbnail);

        return $thumbnailFilePath;
    }

    private function createDir(string $directoryPath): void
    {
        if (!is_dir($directoryPath) && !mkdir($directoryPath) && !is_dir($directoryPath)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $directoryPath));
        }
    }
}
