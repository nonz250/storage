<?php
declare(strict_types=1);

namespace Tests\Adapter\File\Command\DeleteImageByClient;

use Nonz250\Storage\App\Adapter\File\Command\DeleteImageByClient\DeleteImageByClientInput;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;
use PHPUnit\Framework\TestCase;
use Tests\StringTestHelper;

final class DeleteImageByClientInputTest extends TestCase
{
    public function test__construct(): void
    {
        $clientId = StringTestHelper::randomByHex(ClientId::LENGTH);
        $input = new DeleteImageByClientInput(new ClientId($clientId));
        $this->assertSame($clientId, (string)$input->clientId());
    }
}
