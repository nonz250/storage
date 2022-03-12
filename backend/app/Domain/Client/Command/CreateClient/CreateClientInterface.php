<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\Client\Command\CreateClient;

interface CreateClientInterface
{
    public function process(CreateClientInputPort $inputPort): array;
}
