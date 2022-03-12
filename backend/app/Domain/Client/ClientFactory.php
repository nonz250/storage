<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\Client;

use Nonz250\Storage\App\Domain\Client\ValueObject\AppName;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientEmail;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientId;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientSecret;

class ClientFactory implements ClientFactoryInterface
{
    public function newClient(AppName $appName, ClientEmail $clientEmail): Client
    {
        $clientId = ClientId::generate();
        $clientSecret = ClientSecret::generate();
        return new Client($clientId, $clientSecret, $appName, $clientEmail);
    }
}
