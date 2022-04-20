<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File;

use Nonz250\Storage\App\Domain\File\ValueObject\FileName;
use Nonz250\Storage\App\Domain\File\ValueObject\FileString;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;

interface FileFactoryInterface
{
    public function newImageFile(ClientId $clientId, FileName $fileName, FileString $fileString): File;
}
