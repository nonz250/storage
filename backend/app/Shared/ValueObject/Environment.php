<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Shared\ValueObject;

use Nonz250\Storage\App\Foundation\ValueObject\Enum;

class Environment extends Enum
{
    public const LOCAL = 'local';
    public const TESTING = 'testing';
    public const PRODUCTION = 'production';
}
