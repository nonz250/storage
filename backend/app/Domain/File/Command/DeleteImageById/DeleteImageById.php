<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File\Command\DeleteImageById;

use Nonz250\Storage\App\Domain\File\Exceptions\ImageNotExistsException;
use Nonz250\Storage\App\Domain\File\Exceptions\RemoveFileException;
use Nonz250\Storage\App\Domain\File\FileRepositoryInterface;
use Nonz250\Storage\App\Domain\File\FileServiceInterface;
use PDOException;

final class DeleteImageById implements DeleteImageByIdInterface
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

    public function process(DeleteImageByIdInputPort $inputPort): void
    {
        try {
            // 削除する画像データを取得
            $image = $this->fileService->getImageById($inputPort->fileIdentifier());
        } catch (ImageNotExistsException $e) {
            throw new DeleteImageException('Failed to get image file by id.', $e);
        }

        $this->fileRepository->beginTransaction();

        try {
            // データの削除
            $this->fileRepository->delete($image);
        } catch (PDOException $e) {
            $this->fileRepository->rollback();
            throw new DeleteImageException('Failed to delete image record by id.', $e);
        }

        try {
            // ファイルの削除
            $this->fileService->removeImage($image);
            $this->fileRepository->commit();
        } catch (RemoveFileException $e) {
            $this->fileRepository->rollback();
            throw new DeleteImageException('Failed to remove image files.', $e);
        }
    }
}
