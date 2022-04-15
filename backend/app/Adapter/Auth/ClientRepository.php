<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Adapter\Auth;

use Nonz250\Storage\App\Domain\Auth\ClientRepositoryInterface;
use Nonz250\Storage\App\Domain\Client\Client;
use Nonz250\Storage\App\Domain\Client\ValueObject\AppName;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientEmail;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientSecret;
use Nonz250\Storage\App\Foundation\Exceptions\DataNotFoundException;
use Nonz250\Storage\App\Foundation\Model\BindValues;
use Nonz250\Storage\App\Foundation\Model\Model;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;

final class ClientRepository implements ClientRepositoryInterface
{
    private Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function findById(ClientId $clientId): Client
    {
        $sql = 'SELECT * FROM `clients` WHERE id = :client_id';
        $bindValues = new BindValues();
        $bindValues->bindValue(':client_id', (string)$clientId);
        $clients = $this->model->select($sql, $bindValues);
        if (count($clients) === 0) {
            throw new DataNotFoundException(sprintf('%s is not found.', ClientId::NAME));
        }
        $client = $clients[0];
        return new Client(
            new ClientId($client['id']),
            new ClientSecret($client['secret']),
            new AppName($client['app_name']),
            new ClientEmail($client['email']),
        );
    }

    public function create(Client $client): void
    {
        $sql = 'INSERT INTO `clients` (`id`, `secret`, `app_name`, `email`) VALUE (:client_id, :client_secret, :app_name, :email)';
        $bindValues = new BindValues();
        $bindValues->bindValue(':client_id', (string)$client->clientId());
        $bindValues->bindValue(':client_secret', (string)$client->clientSecret());
        $bindValues->bindValue(':app_name', (string)$client->appName());
        $bindValues->bindValue(':email', (string)$client->clientEmail());
        $this->model->insert($sql, $bindValues);
    }
}
