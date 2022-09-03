<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File\Command\DeleteImageByClient;

use Nonz250\Storage\App\Shared\ValueObject\ClientId;

interface DeleteImageByClientInputPort
{
    public function clientId(): ClientId;
}
