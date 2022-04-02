<?php
declare(strict_types=1);

namespace Tests\Adapter\File\Command\UploadImage;

use Nonz250\Storage\App\Adapter\File\Command\UploadImage\UploadImageInput;
use Nonz250\Storage\App\Domain\File\Command\UploadImage\UploadImageInputPort;
use Nonz250\Storage\App\Domain\File\ValueObject\FileName;
use Nonz250\Storage\App\Domain\File\ValueObject\Image;
use PHPUnit\Framework\TestCase;
use Tests\StringTestHelper;

class UploadImageInputTest extends TestCase
{
    public function test__construct()
    {
        $fileName = StringTestHelper::random(FileName::MAX_LENGTH);
        $image = StringTestHelper::random();
        $input = new UploadImageInput(new FileName($fileName), new Image($image));
        $this->assertInstanceOf(UploadImageInputPort::class, $input);
        $this->assertSame($fileName, (string)$input->fileName());
        $this->assertSame($image, (string)$input->image());
        return $input;
    }
}
