<?php
declare(strict_types=1);

namespace Tests\Domain\File\Command\UploadImage;

use Nonz250\Storage\App\Domain\File\Command\UploadImage\UploadImageOutput;
use Nonz250\Storage\App\Domain\File\Command\UploadImage\UploadImageOutputPort;
use Nonz250\Storage\App\Domain\File\ValueObject\FileIdentifier;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;
use Tests\StringTestHelper;

final class UploadImageOutputTest extends TestCase
{
    public function test__construct(): void
    {
        $fileIdentifier = Ulid::generate();
        $originFileName = StringTestHelper::random();
        $fileName = StringTestHelper::random();
        $originPath = StringTestHelper::random();
        $thumbnailPath = StringTestHelper::random();
        $output = new UploadImageOutput(
            new FileIdentifier($fileIdentifier),
            $originFileName,
            $fileName,
            $originPath,
            $thumbnailPath,
        );
        $this->assertInstanceOf(UploadImageOutputPort::class, $output);
        $this->assertSame($fileIdentifier, (string)$output->identifier());
        $this->assertSame($originFileName, $output->originFileName());
        $this->assertSame($fileName, $output->fileName());
        $this->assertSame($originPath, $output->originPath());
        $this->assertSame($thumbnailPath, $output->thumbnailPath());
    }
}
