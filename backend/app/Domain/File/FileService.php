<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File;

use LogicException;
use Nonz250\Storage\App\Domain\File\Exceptions\ImageNotExistsException;
use Nonz250\Storage\App\Domain\File\Exceptions\RemoveFileException;
use Nonz250\Storage\App\Domain\File\Exceptions\UploadFileException;
use Nonz250\Storage\App\Domain\File\ValueObject\FileIdentifier;
use Nonz250\Storage\App\Domain\File\ValueObject\FileName;
use Nonz250\Storage\App\Domain\File\ValueObject\FileString;
use Nonz250\Storage\App\Domain\File\ValueObject\MimeType;
use Nonz250\Storage\App\Foundation\Model\BindValues;
use Nonz250\Storage\App\Foundation\Model\Model;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;
use Psr\Log\LoggerInterface;
use RuntimeException;

final class FileService implements FileServiceInterface
{
    public const UPLOAD_ORIGIN_DIRECTORY = DIRECTORY_SEPARATOR . self::UPLOAD_DIRECTORY . DIRECTORY_SEPARATOR . 'origin';

    public const UPLOAD_THUMBNAIL_DIRECTORY = DIRECTORY_SEPARATOR . self::UPLOAD_DIRECTORY . DIRECTORY_SEPARATOR . 'thumbnail';

    private const UPLOAD_DIRECTORY = 'storage';

    private const FULL_HD_WIDTH = 1920;

    private LoggerInterface $logger;

    private Model $model;

    public function __construct(
        LoggerInterface $logger,
        Model $model
    ) {
        $this->logger = $logger;
        $this->model = $model;
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

    public function getImageById(FileIdentifier $fileIdentifier): File
    {
        $sql = 'SELECT * FROM `files` WHERE `files`.`id` = :file_id';
        $bindValues = new BindValues();
        $bindValues->bindValue(':file_id', (string)$fileIdentifier);
        $file = $this->model->first($sql, $bindValues);
        $fileEntity = new File(
            $fileIdentifier,
            new ClientId((string)($file['client_id'] ?? '')),
            new FileName((string)($file['name'] ?? '')),
            $this->getFileStringById($fileIdentifier, new MimeType((string)($file['origin_mimetype']))),
        );
        $fileEntity->changeThumbnailMimeType(new MimeType((string)$file['thumbnail_mimetype']));
        return $fileEntity;
    }

    public function getImagesByClientId(ClientId $clientId): array
    {
        $sql = 'SELECT * FROM `files` WHERE `files`.`client_id` = :client_id';
        $bindValues = new BindValues();
        $bindValues->bindValue(':client_id', (string)$clientId);
        $files = $this->model->select($sql, $bindValues);
        return array_map(function(array $file) {
            $fileIdentifier = new FileIdentifier((string)($file['id'] ?? ''));
            $fileEntity = new File(
                $fileIdentifier,
                new ClientId((string)($file['client_id'] ?? '')),
                new FileName((string)($file['name'] ?? '')),
                $this->getFileStringById($fileIdentifier, new MimeType((string)($file['origin_mimetype']))),
            );
            $fileEntity->changeThumbnailMimeType(new MimeType((string)$file['thumbnail_mimetype']));
            return $fileEntity;
        }, $files);
    }

    public function removeImage(File $file): void
    {
        $originImageFullPath = getcwd() . self::UPLOAD_ORIGIN_DIRECTORY . DIRECTORY_SEPARATOR . $file->uniqueFileNameWithOriginExtension();
        $thumbnailImageFullPath = getcwd() . self::UPLOAD_THUMBNAIL_DIRECTORY . DIRECTORY_SEPARATOR . $file->uniqueFileNameWithThumbnailExtension();

        if (unlink($originImageFullPath) === false) {
            throw new RemoveFileException('Failed to remove origin image file.');
        }

        if (unlink($thumbnailImageFullPath) === false) {
            throw new RemoveFileException('Failed to remove thumbnail image file.');
        }
    }

    private function createDir(string $directoryPath): void
    {
        if (!is_dir($directoryPath) && !mkdir($directoryPath) && !is_dir($directoryPath)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $directoryPath));
        }
    }

    private function getFileStringById(FileIdentifier $fileIdentifier, MimeType $mimeType): FileString
    {
        $originImageFullPath = getcwd() . self::UPLOAD_ORIGIN_DIRECTORY . DIRECTORY_SEPARATOR . $fileIdentifier . $mimeType->extension();
        $fileString = @file_get_contents($originImageFullPath);

        if ($fileString === false) {
            throw new ImageNotExistsException('Image files not exists.');
        }

        return new FileString($fileString);
    }
}
