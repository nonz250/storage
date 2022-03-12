<?php
declare(strict_types=1);

namespace Tests\Domain\Client\Command\CreateClient;

use Nonz250\Storage\App\Domain\Client\Command\CreateClient\CreateClientInput;
use Nonz250\Storage\App\Domain\Client\Command\CreateClient\CreateClientInputPort;
use Nonz250\Storage\App\Domain\Client\ValueObject\AppName;
use PHPUnit\Framework\TestCase;
use Tests\StringTestHelper;

class CreateClientInputTest extends TestCase
{
    public function test__construct(): CreateClientInputPort
    {
        $appName = StringTestHelper::randomByMb4(AppName::MAX_LENGTH);
        $clientEmail = StringTestHelper::randomEmail();
        $input = new CreateClientInput($appName, $clientEmail);
        $this->assertInstanceOf(CreateClientInputPort::class, $input);
        $this->assertSame($appName, (string)$input->appName());
        $this->assertSame($clientEmail, (string)$input->clientEmail());
        return $input;
    }
}
