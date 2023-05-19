<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
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
use Psr\Log\LoggerInterface;

final class ClientServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
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
    }

    public function boot(): void
    {
        $this->getContainer()
            ->addShared(ClientRepositoryInterface::class, ClientRepository::class)
            ->addArgument(Model::class);

        $this->getContainer()
            ->addShared(DigestAuthInterface::class, DigestAuth::class)
            ->addArgument(ClientRepositoryInterface::class);

        $this->getContainer()
            ->addShared(AuthMiddleware::class)
            ->addArguments([
                LoggerInterface::class,
                DigestAuthInterface::class,
            ]);

        $this->getContainer()
            ->addShared(CreateClientAction::class)
            ->addArguments([
                LoggerInterface::class,
                CreateClientInterface::class,
            ]);

        $this->getContainer()
            ->addShared(ClientFactoryInterface::class, ClientFactory::class);

        $this->getContainer()
            ->addShared(CreateClientInterface::class, CreateClient::class)
            ->addArgument(ClientFactoryInterface::class)
            ->addArgument(ClientRepositoryInterface::class);
    }
}
