<?php
declare(strict_types=1);

namespace Tests\Domain\Client;

use Nonz250\Storage\App\Domain\Client\Client;
use Nonz250\Storage\App\Domain\Client\ValueObject\AppName;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientEmail;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientId;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientSecret;
use PHPUnit\Framework\TestCase;
use Tests\StringTestHelper;

class ClientTest extends TestCase
{
    public function test__construct(): Client
    {
        $clientId = StringTestHelper::randomByHex(ClientId::LENGTH);
        $clientSecret = StringTestHelper::randomByHex(ClientSecret::LENGTH);
        $appName = StringTestHelper::randomByMb4(AppName::MAX_LENGTH);
        $clientEmail = StringTestHelper::randomEmail();
        $client = new Client(
            new ClientId($clientId),
            new ClientSecret($clientSecret),
            new AppName($appName),
            new ClientEmail($clientEmail),
        );
        $this->assertSame($clientId, (string)$client->clientId());
        $this->assertSame($clientSecret, (string)$client->clientSecret());
        $this->assertSame($appName, (string)$client->appName());
        $this->assertSame($clientEmail, (string)$client->clientEmail());
        return $client;
    }
}
