<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\Client;

use Nonz250\Storage\App\Domain\Client\ValueObject\AppName;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientEmail;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientId;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientSecret;

final class Client
{
    private ClientId $clientId;
    private ClientSecret $clientSecret;
    private AppName $appName;
    private ClientEmail $clientEmail;

    public function __construct(
        ClientId $clientId,
        ClientSecret $clientSecret,
        AppName $appName,
        ClientEmail $clientEmail
    ) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->appName = $appName;
        $this->clientEmail = $clientEmail;
    }

    public function clientId(): ClientId
    {
        return $this->clientId;
    }

    public function clientSecret(): ClientSecret
    {
        return $this->clientSecret;
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
