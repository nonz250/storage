<?php
declare(strict_types=1);

namespace Tests\Domain\File;

use Nonz250\Storage\App\Domain\File\FileFactory;
use Nonz250\Storage\App\Domain\File\FileFactoryInterface;
use Nonz250\Storage\App\Domain\File\ValueObject\FileName;
use Nonz250\Storage\App\Domain\File\ValueObject\Image;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;
use PHPUnit\Framework\TestCase;
use Tests\StringTestHelper;

class FileFactoryTest extends TestCase
{
    public function test__construct(): FileFactoryInterface
    {
        $fileFactory = new FileFactory();
        $this->assertInstanceOf(FileFactoryInterface::class, $fileFactory);
        return $fileFactory;
    }

    /**
     * @depends test__construct
     * @param FileFactoryInterface $fileFactory
     * @return void
     */
    public function testNewImageFile(FileFactoryInterface $fileFactory): void
    {
        $clientId = StringTestHelper::randomByHex(ClientId::LENGTH);
        $fileName = StringTestHelper::random();
        $image = StringTestHelper::random();
        $file = $fileFactory->newImageFile(new ClientId($clientId), new FileName($fileName), new Image($image));
        $this->assertIsString((string)$file->identifier());
        $this->assertSame($clientId, (string)$file->clientId());
        $this->assertSame($fileName, (string)$file->fileName());
        $this->assertSame($image, (string)$file->image());
    }
}
