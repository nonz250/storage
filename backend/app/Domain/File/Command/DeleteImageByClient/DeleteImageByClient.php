<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File\Command\DeleteImageByClient;

use Nonz250\Storage\App\Domain\File\Exceptions\ImageNotExistsException;
use Nonz250\Storage\App\Domain\File\Exceptions\RemoveFileException;
use Nonz250\Storage\App\Domain\File\FileRepositoryInterface;
use Nonz250\Storage\App\Domain\File\FileServiceInterface;
use PDOException;
use Throwable;

final class DeleteImageByClient implements DeleteImageByClientInterface
{
    private FileRepositoryInterface $fileRepository;

    private FileServiceInterface $fileService;

    public function __construct(
        FileRepositoryInterface $fileRepository,
        FileServiceInterface $fileService
    ) {
        $this->fileRepository = $fileRepository;
        $this->fileService = $fileService;
    }

    public function process(DeleteImageByClientInputPort $inputPort): void
    {
        try {
            // 削除する画像データを取得
            $images = $this->fileService->getImagesByClientId($inputPort->clientId());
        } catch (ImageNotExistsException $e) {
            throw new DeleteImageException('Failed to get image files by client id.', $e);
        } catch (Throwable $e) {
            throw new DeleteImageException('Internal Server Error.', $e);
        }

        $this->fileRepository->beginTransaction();

        try {
            // データの削除
            // 速度を優先するため `client_id` で削除
            $this->fileRepository->deleteByClientId($inputPort->clientId());
        } catch (PDOException $e) {
            $this->fileRepository->rollback();
            throw new DeleteImageException('Failed to delete image record by client id.', $e);
        } catch (Throwable $e) {
            $this->fileRepository->rollback();
            throw new DeleteImageException('Internal Server Error.', $e);
        }

        try {
            // ファイルの削除
            foreach ($images as $image) {
                $this->fileService->removeImage($image);
            }
            $this->fileRepository->commit();
        } catch (RemoveFileException $e) {
            $this->fileRepository->rollback();
            throw new DeleteImageException('Failed to remove image files.', $e);
        } catch (Throwable $e) {
            $this->fileRepository->rollback();
            throw new DeleteImageException('Internal Server Error.', $e);
        }
    }
}
