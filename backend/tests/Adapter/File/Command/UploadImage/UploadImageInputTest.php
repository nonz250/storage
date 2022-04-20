<?php
declare(strict_types=1);

namespace Tests\Adapter\File\Command\UploadImage;

use Nonz250\Storage\App\Adapter\File\Command\UploadImage\UploadImageInput;
use Nonz250\Storage\App\Domain\File\Command\UploadImage\UploadImageInputPort;
use Nonz250\Storage\App\Domain\File\ValueObject\FileName;
use Nonz250\Storage\App\Domain\File\ValueObject\Image;
use Nonz250\Storage\App\Domain\File\ValueObject\MimeType;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;
use PHPUnit\Framework\TestCase;
use Tests\StringTestHelper;

class UploadImageInputTest extends TestCase
{
    public function test__construct()
    {
        $clientId = StringTestHelper::randomByHex(ClientId::LENGTH);
        $fileName = StringTestHelper::random(FileName::MAX_LENGTH);
        $image = StringTestHelper::random();
        $mimeType = MimeType::MIME_TYPE_JPEG;
        $input = new UploadImageInput(new ClientId($clientId), new FileName($fileName), new Image($image), new MimeType($mimeType));
        $this->assertInstanceOf(UploadImageInputPort::class, $input);
        $this->assertSame($clientId, (string)$input->clientId());
        $this->assertSame($fileName, (string)$input->fileName());
        $this->assertSame($image, (string)$input->image());
        $this->assertSame($mimeType, (string)$input->mimeType());
        return $input;
    }
}
