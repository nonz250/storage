<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\Client\Command\CreateClient;

use Nonz250\Storage\App\Domain\Client\ValueObject\AppName;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientEmail;

final class CreateClientInput implements CreateClientInputPort
{
    private string $appName;
    private string $clientEmail;

    /**
     * @param string $appName
     * @param string $clientEmail
     */
    public function __construct(string $appName, string $clientEmail)
    {
        $this->appName = $appName;
        $this->clientEmail = $clientEmail;
    }

    public function appName(): AppName
    {
        return new AppName($this->appName);
    }

    public function clientEmail(): ClientEmail
    {
        return new ClientEmail($this->clientEmail);
    }
}
