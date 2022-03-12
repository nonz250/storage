<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\Client;

use Nonz250\Storage\App\Domain\Client\ValueObject\AppName;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientEmail;

interface ClientFactoryInterface
{
    public function newClient(AppName $appName, ClientEmail $clientEmail): Client;
}
