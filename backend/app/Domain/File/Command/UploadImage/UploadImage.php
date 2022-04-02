<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File\Command\UploadImage;

use Nonz250\Storage\App\Domain\File\Exceptions\UploadFileException;
use Nonz250\Storage\App\Domain\File\File;
use Nonz250\Storage\App\Domain\File\FileFactoryInterface;
use RuntimeException;

final class UploadImage implements UploadImageInterface
{
    private const UPLOAD_DIRECTORY = 'storage';

    private FileFactoryInterface $fileFactory;

    public function __construct(
        FileFactoryInterface $fileFactory
    ) {
        $this->fileFactory = $fileFactory;
    }

    public function process(UploadImageInputPort $inputPort): File
    {
        $file = $this->fileFactory->newImageFile($inputPort->fileName(), $inputPort->image());

        $uploadDirectory = getcwd() . DIRECTORY_SEPARATOR . self::UPLOAD_DIRECTORY;

        if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory) && !is_dir($uploadDirectory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $uploadDirectory));
        }

        $byte = file_put_contents($uploadDirectory . DIRECTORY_SEPARATOR . $file->fullUniqueFileName(), (string)$file->image());
        if ($byte === false) {
            throw new UploadFileException('Failed to upload file.');
        }
        // TODO: ファイルの容量をログに記録
        // TODO: ファイルの情報をDBに永続化
        return $file;
    }
}
