<?php
declare(strict_types=1);

namespace Tests\Domain\File\Command\UploadImage;

use Nonz250\Storage\App\Domain\File\Command\UploadImage\UploadImageInput;
use Nonz250\Storage\App\Domain\File\Command\UploadImage\UploadImageInputPort;
use Nonz250\Storage\App\Domain\File\ValueObject\FileName;
use Nonz250\Storage\App\Domain\File\ValueObject\FileString;
use Nonz250\Storage\App\Domain\File\ValueObject\MimeType;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;
use PHPUnit\Framework\TestCase;
use Tests\StringTestHelper;

final class UploadImageInputTest extends TestCase
{
    public function test__construct()
    {
        $clientId = StringTestHelper::randomByHex(ClientId::LENGTH);
        $fileName = StringTestHelper::random(FileName::MAX_LENGTH);
        $fileString = StringTestHelper::random();
        $mimeType = MimeType::MIME_TYPE_JPEG;
        $input = new UploadImageInput(new ClientId($clientId), new FileName($fileName), new FileString($fileString), new MimeType($mimeType));
        $this->assertInstanceOf(UploadImageInputPort::class, $input);
        $this->assertSame($clientId, (string)$input->clientId());
        $this->assertSame($fileName, (string)$input->fileName());
        $this->assertSame($fileString, (string)$input->fileString());
        $this->assertSame($mimeType, (string)$input->mimeType());
        return $input;
    }
}
