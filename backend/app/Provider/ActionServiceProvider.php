<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Nonz250\Storage\App\Domain\Client\ClientFactory;
use Nonz250\Storage\App\Domain\Client\Command\CreateClient\CreateClient;
use Nonz250\Storage\App\Domain\Client\Command\CreateClient\CreateClientInterface;
use Nonz250\Storage\App\Http\CreateClient\CreateClientAction;

class ActionServiceProvider extends AbstractServiceProvider
{
    public function provides(string $id): bool
    {
        $services = [
            CreateClientAction::class,
        ];
        return in_array($id, $services, true);
    }

    public function register(): void
    {
        $this->getContainer()
            ->add(CreateClientAction::class)
            ->addArgument(CreateClientInterface::class);

        $this->getContainer()
            ->add(CreateClientInterface::class, CreateClient::class)
            ->addArgument(new ClientFactory());
    }
}
