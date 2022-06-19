<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\Client\Command\CreateClient;

use Nonz250\Storage\App\Domain\Auth\ClientRepositoryInterface;
use Nonz250\Storage\App\Domain\Client\ClientFactoryInterface;
use Nonz250\Storage\App\Domain\Client\Exceptions\CreateClientException;
use Throwable;

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

        try {
            $this->clientRepository->beginTransaction();
            $this->clientRepository->create($client);
            $this->clientRepository->commit();
        } catch (Throwable $e) {
            $this->clientRepository->rollback();
            throw new CreateClientException('Failed to create client.');
        }
        return [
            'clientId' => (string)$client->clientId(),
            'clientSecret' => (string)$client->clientSecret(),
            'appName' => (string)$client->appName(),
            'clientEmail' => (string)$client->clientEmail(),
        ];
    }
}
