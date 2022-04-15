<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\Auth;

use Nonz250\Storage\App\Domain\Client\Client;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;

interface ClientRepositoryInterface
{
    public function findById(ClientId $clientId): Client;

    public function create(Client $client): void;
}
