<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File;

use Nonz250\Storage\App\Domain\File\ValueObject\FileIdentifier;
use Nonz250\Storage\App\Domain\File\ValueObject\FileName;
use Nonz250\Storage\App\Domain\File\ValueObject\FileString;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;
use Symfony\Component\Uid\Ulid;

final class FileFactory implements FileFactoryInterface
{
    public function newImageFile(ClientId $clientId, FileName $fileName, FileString $fileString): File
    {
        return new File(new FileIdentifier(Ulid::generate()), $clientId, $fileName, $fileString);
    }
}
