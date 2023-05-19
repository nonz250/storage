<?php
declare(strict_types=1);

namespace Tests;

use League\Container\DefinitionContainerInterface;
use Nonz250\Storage\App\Domain\Auth\ClientRepositoryInterface;
use Nonz250\Storage\App\Domain\Client\Client;
use Nonz250\Storage\App\Domain\Client\ClientFactoryInterface;
use Nonz250\Storage\App\Domain\Client\ValueObject\AppName;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientEmail;
use Nonz250\Storage\App\Foundation\Model\Model;
use PHPUnit\Framework\TestCase;

abstract class RepositoryTestCase extends TestCase
{
    use RepositoryTestTrait;

    protected Client $client;

    private ?DefinitionContainerInterface $container = null;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var Model $model */
        $model = $this->make(Model::class);
        $model->beginTransaction();

        /** @var ClientFactoryInterface $clientFactory */
        $clientFactory = $this->make(ClientFactoryInterface::class);
        $client = $clientFactory->newClient(
            new AppName('For testing app'),
            new ClientEmail(StringTestHelper::randomEmail()),
        );

        /** @var ClientRepositoryInterface $clientRepository */
        $clientRepository = $this->make(ClientRepositoryInterface::class);
        $clientRepository->create($client);
        $this->client = $client;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        /** @var Model $model */
        $model = $this->make(Model::class);
        $model->rollBack();
    }
}
