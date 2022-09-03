<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File\Command\DeleteImageByClient;

use Nonz250\Storage\App\Domain\File\Exceptions\ImageNotExistsException;
use Nonz250\Storage\App\Domain\File\Exceptions\RemoveFileException;
use Nonz250\Storage\App\Domain\File\FileRepositoryInterface;
use Nonz250\Storage\App\Domain\File\FileServiceInterface;
use PDOException;
use Psr\Log\LoggerInterface;

final class DeleteImageByClient implements DeleteImageByClientInterface
{
    private LoggerInterface $logger;

    private FileRepositoryInterface $fileRepository;

    private FileServiceInterface $fileService;

    public function __construct(
        LoggerInterface $logger,
        FileRepositoryInterface $fileRepository,
        FileServiceInterface $fileService
    ) {
        $this->logger = $logger;
        $this->fileRepository = $fileRepository;
        $this->fileService = $fileService;
    }

    public function process(DeleteImageByClientInputPort $inputPort): void
    {
        try {
            // 削除する画像ファイルを取得
            $images = $this->fileService->getImagesByClientId($inputPort->clientId());
        } catch (ImageNotExistsException $e) {
            $this->logger->error($e);
            throw new DeleteImageException('Failed to get image files by client id.');
        }

        try {
            // データの削除
            $this->fileRepository->beginTransaction();
            // 速度を優先するため `client_id` で削除
            $this->fileRepository->deleteByClientId($inputPort->clientId());
        } catch (PDOException $e) {
            $this->fileRepository->rollback();
            $this->logger->error($e);
            throw new DeleteImageException('Failed to delete image record by client id.');
        }

        try {
            // ファイルの削除
            foreach ($images as $image) {
                $this->fileService->removeImage($image);
            }
            $this->fileRepository->commit();
        } catch (RemoveFileException $e) {
            $this->fileRepository->rollback();
            $this->logger->error($e);
            throw new DeleteImageException('Failed to remove image files.');
        }
    }
}
