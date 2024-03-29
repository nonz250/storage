<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Nonz250\Storage\App\Adapter\File\FileRepository;
use Nonz250\Storage\App\Domain\File\Command\DeleteImageByClient\DeleteImageByClient;
use Nonz250\Storage\App\Domain\File\Command\DeleteImageByClient\DeleteImageByClientInterface;
use Nonz250\Storage\App\Domain\File\Command\DeleteImageById\DeleteImageById;
use Nonz250\Storage\App\Domain\File\Command\DeleteImageById\DeleteImageByIdInterface;
use Nonz250\Storage\App\Domain\File\Command\UploadImage\UploadImage;
use Nonz250\Storage\App\Domain\File\Command\UploadImage\UploadImageInterface;
use Nonz250\Storage\App\Domain\File\FileFactory;
use Nonz250\Storage\App\Domain\File\FileFactoryInterface;
use Nonz250\Storage\App\Domain\File\FileRepositoryInterface;
use Nonz250\Storage\App\Domain\File\FileService;
use Nonz250\Storage\App\Domain\File\FileServiceInterface;
use Nonz250\Storage\App\Foundation\Model\Model;
use Nonz250\Storage\App\Http\DeleteFileByClient\DeleteFileByClientAction;
use Nonz250\Storage\App\Http\DeleteFileById\DeleteFileByIdAction;
use Nonz250\Storage\App\Http\UploadFile\UploadFileAction;
use Psr\Log\LoggerInterface;

final class FileServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    public function provides(string $id): bool
    {
        $services = [
            UploadFileAction::class,
            DeleteFileByClientAction::class,
            DeleteFileByIdAction::class,
        ];
        return in_array($id, $services, true);
    }

    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->getContainer()
            ->addShared(FileRepositoryInterface::class, FileRepository::class)
            ->addArgument(Model::class);

        $this->getContainer()
            ->addShared(UploadFileAction::class)
            ->addArguments([
                LoggerInterface::class,
                UploadImageInterface::class,
            ]);

        $this->getContainer()
            ->addShared(DeleteFileByClientAction::class)
            ->addArguments([
                LoggerInterface::class,
                DeleteImageByClientInterface::class,
            ]);

        $this->getContainer()
            ->addShared(DeleteFileByIdAction::class)
            ->addArguments([
                LoggerInterface::class,
                DeleteImageByIdInterface::class,
            ]);

        $this->getContainer()
            ->addShared(FileFactoryInterface::class, FileFactory::class);

        $this->getContainer()
            ->addShared(FileServiceInterface::class, FileService::class)
            ->addArgument(LoggerInterface::class)
            ->addArgument(Model::class);

        $this->getContainer()
            ->addShared(UploadImageInterface::class, UploadImage::class)
            ->addArguments([
                LoggerInterface::class,
                FileFactoryInterface::class,
                FileRepositoryInterface::class,
                FileServiceInterface::class,
            ]);

        $this->getContainer()
            ->addShared(DeleteImageByClientInterface::class, DeleteImageByClient::class)
            ->addArguments([
                FileRepositoryInterface::class,
                FileServiceInterface::class,
            ]);

        $this->getContainer()
            ->addShared(DeleteImageByIdInterface::class, DeleteImageById::class)
            ->addArguments([
                FileRepositoryInterface::class,
                FileServiceInterface::class,
            ]);
    }
}
