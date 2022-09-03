<?php
declare(strict_types=1);

namespace Tests\Domain\File\Command\DeleteImageById;

use Nonz250\Storage\App\Domain\File\Command\DeleteImageById\DeleteImageByIdInput;
use Nonz250\Storage\App\Domain\File\ValueObject\FileIdentifier;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;
use Tests\StringTestHelper;

final class DeleteImageByIdInputTest extends TestCase
{
    public function test__construct(): void
    {
        $clientId = StringTestHelper::randomByHex(ClientId::LENGTH);
        $fileIdentifier = Ulid::generate();
        $input = new DeleteImageByIdInput(new ClientId($clientId), new FileIdentifier($fileIdentifier));
        $this->assertSame($clientId, (string)$input->clientId());
        $this->assertSame($fileIdentifier, (string)$input->fileIdentifier());
    }
}
