<?php
declare(strict_types=1);

namespace Tests\Adapter\Auth;

use Nonz250\Storage\App\Domain\Auth\ClientRepositoryInterface;
use Nonz250\Storage\App\Domain\Client\Client;
use Nonz250\Storage\App\Domain\Client\ValueObject\AppName;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientEmail;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientSecret;
use Nonz250\Storage\App\Foundation\Exceptions\DataNotFoundException;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;
use Tests\RepositoryTestCase;
use Tests\StringTestHelper;

final class ClientRepositoryRepositoryTest extends RepositoryTestCase
{
    public function testNotExists(): void
    {
        $this->expectException(DataNotFoundException::class);
        $clientRepository = $this->make(ClientRepositoryInterface::class);
        $this->assertInstanceOf(ClientRepositoryInterface::class, $clientRepository);
        $clientRepository->findById(new ClientId(StringTestHelper::randomByHex(ClientId::LENGTH)));
    }

    public function testCrud(): void
    {
        $clientRepository = $this->make(ClientRepositoryInterface::class);
        $this->assertInstanceOf(ClientRepositoryInterface::class, $clientRepository);

        $clientId = StringTestHelper::randomByHex(ClientId::LENGTH);
        $clientSecret = StringTestHelper::randomByHex(ClientSecret::LENGTH);
        $appName = StringTestHelper::random(AppName::MAX_LENGTH);
        $clientEmail = StringTestHelper::randomEmail();
        $clientRepository->create(
            new Client(
                new ClientId($clientId),
                new ClientSecret($clientSecret),
                new AppName($appName),
                new ClientEmail($clientEmail),
            )
        );

        $client = $clientRepository->findById(new ClientId($clientId));
        $this->assertSame($clientId, (string)$client->clientId());
        $this->assertSame($clientSecret, (string)$client->clientSecret());
        $this->assertSame($appName, (string)$client->appName());
        $this->assertSame($clientEmail, (string)$client->clientEmail());
    }
}
