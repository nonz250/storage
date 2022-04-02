<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Nonz250\Storage\App\Domain\File\Command\UploadImage\UploadImage;
use Nonz250\Storage\App\Domain\File\Command\UploadImage\UploadImageInterface;
use Nonz250\Storage\App\Domain\File\FileFactory;
use Nonz250\Storage\App\Domain\File\FileFactoryInterface;
use Nonz250\Storage\App\Http\UploadFile\UploadFileAction;

class FileServiceProvider extends AbstractServiceProvider
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
//        $this->getContainer()
//            ->add(ClientRepositoryInterface::class, ClientRepository::class)
//            ->addArgument(Model::class);

//        $this->getContainer()
//            ->add(DigestAuthInterface::class, DigestAuth::class)
//            ->addArgument(ClientRepositoryInterface::class);

//        $this->getContainer()
//            ->add(AuthMiddleware::class)
//            ->addArgument(DigestAuthInterface::class);

        $this->getContainer()
            ->add(UploadFileAction::class)
            ->addArgument(UploadImageInterface::class);

        $this->getContainer()
            ->add(FileFactoryInterface::class, FileFactory::class);

        $this->getContainer()
            ->add(UploadImageInterface::class, UploadImage::class)
            ->addArgument(FileFactoryInterface::class);
//            ->addArgument(ClientRepositoryInterface::class);
    }
}
