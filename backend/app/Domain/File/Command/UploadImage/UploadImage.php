<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File\Command\UploadImage;

use Nonz250\Storage\App\Domain\File\Exceptions\UploadFileException;
use Nonz250\Storage\App\Domain\File\File;
use Nonz250\Storage\App\Domain\File\FileFactoryInterface;
use Nonz250\Storage\App\Domain\File\FileRepositoryInterface;
use Nonz250\Storage\App\Domain\File\FileServiceInterface;
use Nonz250\Storage\App\Domain\File\ValueObject\MimeType;
use PDOException;
use Psr\Log\LoggerInterface;

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

    public function process(UploadImageInputPort $inputPort): File
    {
        $file = $this->fileFactory->newImageFile($inputPort->clientId(), $inputPort->fileName(), $inputPort->image());

        try {
            $this->fileRepository->create($file);
        } catch (PDOException $e) {
            $this->logger->error($e);
            throw new UploadFileException('Failed to register database.');
        }

        $originFilePath = $this->fileService->uploadOriginImage($file);
        $this->logger->debug($originFilePath);

        $thumbnailFilePath = $this->fileService->uploadThumbnailImage($file, new MimeType(MimeType::MIME_TYPE_WEBP));
        $this->logger->debug($thumbnailFilePath);

        return $file;
    }
}
