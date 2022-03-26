<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\Client\Command\CreateClient;

use Nonz250\Storage\App\Domain\Auth\ClientRepositoryInterface;
use Nonz250\Storage\App\Domain\Client\ClientFactoryInterface;

final class CreateClient implements CreateClientInterface
{
    private ClientFactoryInterface $clientFactory;
    private ClientRepositoryInterface $clientRepository;

    public function __construct(
        ClientFactoryInterface $clientFactory,
        ClientRepositoryInterface $clientRepository
    ) {
        $this->clientFactory = $clientFactory;
        $this->clientRepository = $clientRepository;
    }

    public function process(CreateClientInputPort $inputPort): array
    {
        $client = $this->clientFactory->newClient($inputPort->appName(), $inputPort->clientEmail());
        $this->clientRepository->create($client);
        return [
            'clientId' => (string)$client->clientId(),
            'clientSecret' => (string)$client->clientSecret(),
            'appName' => (string)$client->appName(),
            'clientEmail' => (string)$client->clientEmail(),
        ];
    }
}
