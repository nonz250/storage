<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Nonz250\Storage\App\Adapter\Auth\ClientRepository;
use Nonz250\Storage\App\Domain\Auth\ClientRepositoryInterface;
use Nonz250\Storage\App\Domain\Auth\Command\DigestAuth\DigestAuth;
use Nonz250\Storage\App\Domain\Auth\Command\DigestAuth\DigestAuthInterface;
use Nonz250\Storage\App\Domain\Client\ClientFactory;
use Nonz250\Storage\App\Domain\Client\ClientFactoryInterface;
use Nonz250\Storage\App\Domain\Client\Command\CreateClient\CreateClient;
use Nonz250\Storage\App\Domain\Client\Command\CreateClient\CreateClientInterface;
use Nonz250\Storage\App\Foundation\Model\Model;
use Nonz250\Storage\App\Http\Auth\AuthMiddleware;
use Nonz250\Storage\App\Http\CreateClient\CreateClientAction;

class ClientServiceProvider extends AbstractServiceProvider
{
    public function provides(string $id): bool
    {
        $services = [
            AuthMiddleware::class,
            CreateClientAction::class,
        ];
        return in_array($id, $services, true);
    }

    public function register(): void
    {
        $this->getContainer()
            ->add(ClientRepositoryInterface::class, ClientRepository::class)
            ->addArgument(Model::class);

        $this->getContainer()
            ->add(DigestAuthInterface::class, DigestAuth::class)
            ->addArgument(ClientRepositoryInterface::class);

        $this->getContainer()
            ->add(AuthMiddleware::class)
            ->addArgument(DigestAuthInterface::class);

        $this->getContainer()
            ->add(CreateClientAction::class)
            ->addArgument(CreateClientInterface::class);

        $this->getContainer()
            ->add(ClientFactoryInterface::class, ClientFactory::class);

        $this->getContainer()
            ->add(CreateClientInterface::class, CreateClient::class)
            ->addArgument(ClientFactoryInterface::class)
            ->addArgument(ClientRepositoryInterface::class);
    }
}
