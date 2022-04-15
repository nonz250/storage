<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File\Command\UploadImage;

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

        $uploadDirectory = getcwd() . DIRECTORY_SEPARATOR . self::UPLOAD_DIRECTORY;

        if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory) && !is_dir($uploadDirectory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $uploadDirectory));
        }

        $byte = file_put_contents($uploadDirectory . DIRECTORY_SEPARATOR . $file->fullUniqueFileName(), (string)$file->image());
        if ($byte === false) {
            throw new UploadFileException('Failed to upload file.');
        }

        $this->logger->info(sprintf('%s is %s bytes.', $file->fullFileName(), $byte));

        return $file;
    }
}
