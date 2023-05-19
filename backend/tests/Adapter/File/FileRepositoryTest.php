<?php
declare(strict_types=1);

namespace Tests\Adapter\File;

use Nonz250\Storage\App\Domain\File\FileFactoryInterface;
use Nonz250\Storage\App\Domain\File\FileRepositoryInterface;
use Nonz250\Storage\App\Domain\File\ValueObject\FileName;
use Nonz250\Storage\App\Domain\File\ValueObject\FileString;
use Tests\RepositoryTestCase;
use Tests\StringTestHelper;

final class FileRepositoryTest extends RepositoryTestCase
{
    public function testCrud(): void
    {
        $fileFactory = $this->make(FileFactoryInterface::class);
        $this->assertInstanceOf(FileFactoryInterface::class, $fileFactory);

        $fileRepository = $this->make(FileRepositoryInterface::class);
        $this->assertInstanceOf(FileRepositoryInterface::class, $fileRepository);

        $expectedCount = 10;
        $expectedFiles = [];

        for ($i = 0; $i < $expectedCount; $i++) {
            $fileName = StringTestHelper::random(FileName::MAX_LENGTH);
            $fileString = StringTestHelper::randomImageString();

            $expectedFile = $fileFactory->newImageFile(
                $this->client->clientId(),
                new FileName($fileName),
                new FileString($fileString),
            );
            $fileRepository->create($expectedFile);
            $expectedFiles[] = $expectedFile;
        }

        $fileRepository->deleteByClientId($expectedFiles[0]->clientId());

        foreach ($expectedFiles as $index => $expectedFile) {
            if ($index === 0) {
                continue;
            }
            $fileRepository->delete($expectedFile);
        }
    }
}
