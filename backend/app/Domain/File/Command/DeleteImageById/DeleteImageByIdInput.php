<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File\Command\DeleteImageById;

use Nonz250\Storage\App\Domain\File\ValueObject\FileIdentifier;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;

final class DeleteImageByIdInput implements DeleteImageByIdInputPort
{
    private ClientId $clientId;

    private FileIdentifier $fileIdentifier;

    public function __construct(ClientId $clientId, FileIdentifier $fileIdentifier)
    {
        $this->clientId = $clientId;
        $this->fileIdentifier = $fileIdentifier;
    }

    public function clientId(): ClientId
    {
        return $this->clientId;
    }

    public function fileIdentifier(): FileIdentifier
    {
        return $this->fileIdentifier;
    }
}
