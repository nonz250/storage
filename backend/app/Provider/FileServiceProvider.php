<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Nonz250\Storage\App\Adapter\File\FileRepository;
use Nonz250\Storage\App\Domain\File\Command\UploadImage\UploadImage;
use Nonz250\Storage\App\Domain\File\Command\UploadImage\UploadImageInterface;
use Nonz250\Storage\App\Domain\File\FileFactory;
use Nonz250\Storage\App\Domain\File\FileFactoryInterface;
use Nonz250\Storage\App\Domain\File\FileRepositoryInterface;
use Nonz250\Storage\App\Domain\File\FileService;
use Nonz250\Storage\App\Domain\File\FileServiceInterface;
use Nonz250\Storage\App\Foundation\Model\Model;
use Nonz250\Storage\App\Http\UploadFile\UploadFileAction;
use Psr\Log\LoggerInterface;

final class FileServiceProvider extends AbstractServiceProvider
{
    public function provides(string $id): bool
    {
        $services = [
            UploadFileAction::class,
        ];
        return in_array($id, $services, true);
    }

    public function register(): void
    {
        $this->getContainer()
            ->add(FileRepositoryInterface::class, FileRepository::class)
            ->addArgument(Model::class);

        $this->getContainer()
            ->add(UploadFileAction::class)
            ->addArguments([
                LoggerInterface::class,
                UploadImageInterface::class,
            ]);

        $this->getContainer()
            ->add(FileFactoryInterface::class, FileFactory::class);

        $this->getContainer()
            ->add(FileServiceInterface::class, FileService::class)
            ->addArgument(LoggerInterface::class);

        $this->getContainer()
            ->add(UploadImageInterface::class, UploadImage::class)
            ->addArguments([
                LoggerInterface::class,
                FileFactoryInterface::class,
                FileRepositoryInterface::class,
                FileServiceInterface::class,
            ]);
    }
}
