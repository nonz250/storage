<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File\Command\DeleteImageById;

use Nonz250\Storage\App\Domain\File\ValueObject\FileIdentifier;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;

interface DeleteImageByIdInputPort
{
    public function clientId(): ClientId;

    public function fileIdentifier(): FileIdentifier;
}
