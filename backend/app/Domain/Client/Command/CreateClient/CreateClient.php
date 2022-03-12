<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\Client\Command\CreateClient;

use Nonz250\Storage\App\Domain\Client\ClientFactoryInterface;

final class CreateClient implements CreateClientInterface
{
    private ClientFactoryInterface $clientFactory;

    public function __construct(
        ClientFactoryInterface $clientFactory
    ) {
        $this->clientFactory = $clientFactory;
    }

    public function process(CreateClientInputPort $inputPort): array
    {
        $client = $this->clientFactory->newClient($inputPort->appName(), $inputPort->clientEmail());
        // TODO: 永続化対応
        return [
            'clientId' => (string)$client->clientId(),
            'clientSecret' => (string)$client->clientSecret(),
            'appName' => (string)$client->appName(),
            'clientEmail' => (string)$client->clientEmail(),
        ];
    }
}
