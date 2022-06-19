<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\Client\Command\CreateClient;

use Nonz250\Storage\App\Domain\Client\ValueObject\AppName;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientEmail;

final class CreateClientInput implements CreateClientInputPort
{
    private AppName $appName;

    private ClientEmail $clientEmail;

    public function __construct(AppName $appName, ClientEmail $clientEmail)
    {
        $this->appName = $appName;
        $this->clientEmail = $clientEmail;
    }

    public function appName(): AppName
    {
        return $this->appName;
    }

    public function clientEmail(): ClientEmail
    {
        return $this->clientEmail;
    }
}
