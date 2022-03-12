<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\Client\ValueObject;

use Nonz250\Storage\App\Foundation\ValueObject\EmailValue;

final class ClientEmail extends EmailValue
{
    public function __construct(string $value)
    {
        $this->value = $value;
    }
}
