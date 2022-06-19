<?php
declare(strict_types=1);

namespace Tests\Domain\Client;

use Nonz250\Storage\App\Domain\Client\ClientFactory;
use Nonz250\Storage\App\Domain\Client\ClientFactoryInterface;
use Nonz250\Storage\App\Domain\Client\ValueObject\AppName;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientEmail;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientSecret;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;
use PHPUnit\Framework\TestCase;
use Tests\StringTestHelper;

final class ClientFactoryTest extends TestCase
{
    public function test__construct(): ClientFactoryInterface
    {
        $factory = new ClientFactory();
        $this->assertInstanceOf(ClientFactoryInterface::class, $factory);
        return $factory;
    }

    /**
     * @depends test__construct
     *
     * @param ClientFactoryInterface $clientFactory
     *
     * @return void
     */
    public function testNewClient(ClientFactoryInterface $clientFactory): void
    {
        $appName = StringTestHelper::randomByMb4(AppName::MAX_LENGTH);
        $clientEmail = StringTestHelper::randomEmail();
        $client = $clientFactory->newClient(new AppName($appName), new ClientEmail($clientEmail));
        $this->assertSame(ClientId::LENGTH, $client->clientId()->count());
        $this->assertSame(ClientSecret::LENGTH, $client->clientSecret()->count());
        $this->assertSame($appName, (string)$client->appName());
        $this->assertSame($clientEmail, (string)$client->clientEmail());
    }
}
