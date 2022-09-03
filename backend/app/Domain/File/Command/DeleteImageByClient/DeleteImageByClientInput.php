<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File\Command\DeleteImageByClient;

use Nonz250\Storage\App\Shared\ValueObject\ClientId;

final class DeleteImageByClientInput implements DeleteImageByClientInputPort
{
    private ClientId $clientId;

    public function __construct(ClientId $clientId)
    {
        $this->clientId = $clientId;
    }

    public function clientId(): ClientId
    {
        return $this->clientId;
    }
}
