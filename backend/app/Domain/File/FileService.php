<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File;

use LogicException;
use Nonz250\Storage\App\Domain\File\Exceptions\UploadFileException;
use Nonz250\Storage\App\Domain\File\ValueObject\MimeType;
use Psr\Log\LoggerInterface;
use RuntimeException;

class FileService implements FileServiceInterface
{
    private const UPLOAD_DIRECTORY = 'storage';
    private const UPLOAD_ORIGIN_DIRECTORY = 'origin';
    private const UPLOAD_THUMBNAIL_DIRECTORY = 'thumbnail';
    private const FULL_HD_WIDTH = 1920;

    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function uploadOriginImage(File $file): string
    {
        $uploadStorageDirectory = getcwd() . DIRECTORY_SEPARATOR . self::UPLOAD_DIRECTORY . DIRECTORY_SEPARATOR . self::UPLOAD_ORIGIN_DIRECTORY;
        $this->createDir($uploadStorageDirectory);

        $originFilePath = $uploadStorageDirectory . DIRECTORY_SEPARATOR . $file->fullUniqueFileName();
        $byte = file_put_contents($originFilePath, (string)$file->image());
        if ($byte === false) {
            throw new UploadFileException('Failed to upload file.');
        }

        $this->logger->debug(sprintf('%s is %s bytes.', $file->fullFileName(), $byte));

        return $originFilePath;
    }

    public function uploadThumbnailImage(File $file, ?MimeType $mimeType = null, int $resizeWidth = self::FULL_HD_WIDTH / 2): string
    {
        $mimeType = $mimeType ?? $file->mimeType();

        $uploadThumbnailDirectory = getcwd() . DIRECTORY_SEPARATOR . self::UPLOAD_DIRECTORY . DIRECTORY_SEPARATOR . self::UPLOAD_THUMBNAIL_DIRECTORY;
        $this->createDir($uploadThumbnailDirectory);

        [$originWidth, $originHeight, $type] = getimagesizefromstring((string)$file->image());
        $this->logger->debug(sprintf('width: %s, height: %s, type: %s -- %s', $originWidth, $originHeight, $type, $file->identifier()));

        $source = imagecreatefromstring((string)$file->image());
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

        $thumbnailFilePath = $uploadThumbnailDirectory . DIRECTORY_SEPARATOR . $file->fullUniqueFileName($mimeType);
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
            // @coverageIgnoreStart
            $this->logger->error(sprintf('Unknown mimetype. [%s]', $mimeType));
            throw new LogicException('Unknown mimetype.');
            // @coverageIgnoreEnd
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
