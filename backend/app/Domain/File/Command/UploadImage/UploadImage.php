<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File\Command\UploadImage;

use Nonz250\Storage\App\Domain\File\Exceptions\UploadFileException;
use Nonz250\Storage\App\Domain\File\FileFactoryInterface;
use Nonz250\Storage\App\Domain\File\FileRepositoryInterface;
use Nonz250\Storage\App\Domain\File\FileService;
use Nonz250\Storage\App\Domain\File\FileServiceInterface;
use Psr\Log\LoggerInterface;
use Throwable;

final class UploadImage implements UploadImageInterface
{
    private LoggerInterface $logger;

    private FileFactoryInterface $fileFactory;

    private FileRepositoryInterface $fileRepository;

    private FileServiceInterface $fileService;

    public function __construct(
        LoggerInterface $logger,
        FileFactoryInterface $fileFactory,
        FileRepositoryInterface $fileRepository,
        FileServiceInterface $fileService
    ) {
        $this->logger = $logger;
        $this->fileFactory = $fileFactory;
        $this->fileRepository = $fileRepository;
        $this->fileService = $fileService;
    }

    public function process(UploadImageInputPort $inputPort): UploadImageOutputPort
    {
        $file = $this->fileFactory->newImageFile($inputPort->clientId(), $inputPort->fileName(), $inputPort->fileString());

        // Save Webp extension.
        $file->changeThumbnailMimeType($inputPort->mimeType());

        try {
            $this->fileRepository->beginTransaction();
            $this->fileRepository->create($file);
        } catch (Throwable $e) {
            $this->fileRepository->rollback();
            $this->logger->error($e);
            throw new UploadFileException('Failed to register database.');
        }

        try {
            $originFilePath = $this->fileService->uploadOriginImage($file);
            $this->logger->debug($originFilePath);
        } catch (Throwable $e) {
            $this->fileRepository->rollback();
            $this->logger->error($e);
            throw new UploadFileException('Failed to upload origin file.');
        }

        try {
            $thumbnailFilePath = $this->fileService->uploadThumbnailImage($file);
            $this->logger->debug($thumbnailFilePath);
        } catch (Throwable $e) {
            $this->fileRepository->rollback();

            if (!unlink($originFilePath)) {
                $this->logger->error(sprintf('Failed to delete origin file. -- %s', $originFilePath));
            }
            $this->logger->error($e);
            throw new UploadFileException('Failed to upload thumbnail file.');
        }

        $this->fileRepository->commit();

        return new UploadImageOutput(
            $file->identifier(),
            $file->fileNameWithOriginExtension(),
            $file->uniqueFileNameWithOriginExtension(),
            FileService::UPLOAD_ORIGIN_DIRECTORY . DIRECTORY_SEPARATOR . $file->uniqueFileNameWithOriginExtension(),
            FileService::UPLOAD_THUMBNAIL_DIRECTORY . DIRECTORY_SEPARATOR . $file->uniqueFileNameWithThumbnailExtension(),
        );
    }
}
