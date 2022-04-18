<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File\Command\UploadImage;

use LogicException;
use Nonz250\Storage\App\Domain\File\Exceptions\UploadFileException;
use Nonz250\Storage\App\Domain\File\File;
use Nonz250\Storage\App\Domain\File\FileFactoryInterface;
use Nonz250\Storage\App\Domain\File\FileRepositoryInterface;
use PDOException;
use Psr\Log\LoggerInterface;
use RuntimeException;

final class UploadImage implements UploadImageInterface
{
    private const UPLOAD_DIRECTORY = 'storage';
    private const UPLOAD_ORIGIN_DIRECTORY = 'origin';
    private const UPLOAD_THUMBNAIL_DIRECTORY = 'thumbnail';
    private const FULL_HD_WIDTH = 1920;

    private LoggerInterface $logger;
    private FileFactoryInterface $fileFactory;
    private FileRepositoryInterface $fileRepository;

    public function __construct(
        LoggerInterface $logger,
        FileFactoryInterface $fileFactory,
        FileRepositoryInterface $fileRepository
    ) {
        $this->logger = $logger;
        $this->fileFactory = $fileFactory;
        $this->fileRepository = $fileRepository;
    }

    public function process(UploadImageInputPort $inputPort): File
    {
        $file = $this->fileFactory->newImageFile($inputPort->clientId(), $inputPort->fileName(), $inputPort->image());

        try {
            $this->fileRepository->create($file);
        } catch (PDOException $e) {
            throw new UploadFileException('Failed to register database.');
        }

        $uploadStorageDirectory = getcwd() . DIRECTORY_SEPARATOR . self::UPLOAD_DIRECTORY . DIRECTORY_SEPARATOR . self::UPLOAD_ORIGIN_DIRECTORY;
        if (!is_dir($uploadStorageDirectory) && !mkdir($uploadStorageDirectory) && !is_dir($uploadStorageDirectory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $uploadStorageDirectory));
        }

        $uploadThumbnailDirectory = getcwd() . DIRECTORY_SEPARATOR . self::UPLOAD_DIRECTORY . DIRECTORY_SEPARATOR . self::UPLOAD_THUMBNAIL_DIRECTORY;
        if (!is_dir($uploadThumbnailDirectory) && !mkdir($uploadThumbnailDirectory) && !is_dir($uploadThumbnailDirectory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $uploadStorageDirectory));
        }

        $originFilePath = $uploadStorageDirectory . DIRECTORY_SEPARATOR . $file->fullUniqueFileName();

        $byte = file_put_contents($originFilePath, (string)$file->image());
        if ($byte === false) {
            throw new UploadFileException('Failed to upload file.');
        }
        $this->logger->info(sprintf('%s is %s bytes.', $file->fullFileName(), $byte));

        // サムネイル画像の作成開始
        [$originWidth, $originHeight, $type] = getimagesize($originFilePath);
        $this->logger->debug(sprintf('width: %s, height: %s, type: %s -- %s', $originWidth, $originHeight, $type, $originFilePath));

        // Mimetypeからソースとなる画像を取得
        $mimeType = $file->mimeType();
        if ($mimeType->isBmp()) {
            $source = imagecreatefrombmp($originFilePath);
        } elseif ($mimeType->isGif()) {
            $source = imagecreatefromgif($originFilePath);
        } elseif ($mimeType->isJpeg()) {
            $source = imagecreatefromjpeg($originFilePath);
        } elseif ($mimeType->isPng()) {
            $source = imagecreatefrompng($originFilePath);
        } elseif ($mimeType->isWebp()) {
            $source = imagecreatefromwebp($originFilePath);
        } else {
            // @coverageIgnoreStart
            $this->logger->error(sprintf('Unknown mimetype. [%s]', $mimeType));
            throw new LogicException('Unknown mimetype.');
            // @coverageIgnoreEnd
        }

        if ($source === false) {
            throw new UploadFileException('Failed to upload file.');
        }

        // TODO: 幅を指定できるようにしたい。
        $ratio = $originWidth >= (self::FULL_HD_WIDTH / 2) ? (self::FULL_HD_WIDTH / 2) / $originWidth : 1;
        $newWidth = (int)($originWidth * $ratio);
        $newHeight = (int)($originHeight * $ratio);

        // 元の画像をベースに縮小した画像を生成する
        $thumbnail = imagecreatetruecolor($newWidth, $newHeight);
        $result = imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, $newWidth, $newHeight, $originWidth, $originHeight);
        if (!$result) {
            throw new UploadFileException('Failed to upload file.');
        }

        // TODO: 拡張子をwebpに変更できるようにしたい
        $thumbnailFilePath = $uploadThumbnailDirectory . DIRECTORY_SEPARATOR . $file->fullUniqueFileName();

        // TODO: webp形式のみにしたい。
        // TODO: 引数で拡張子を指定できるようにしたい。
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

        return $file;
    }
}
