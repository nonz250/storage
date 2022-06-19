<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\Client\ValueObject;

use InvalidArgumentException;
use Nonz250\Storage\App\Foundation\ValueObject\StringValue;

final class AppName extends StringValue
{
    public const MAX_LENGTH = 20;

    private const NAME = 'appName';

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    protected function validate(string $value): void
    {
        if ($value === '') {
            throw new InvalidArgumentException(sprintf('%s is required.', self::NAME));
        }

        if (mb_strlen($value) > self::MAX_LENGTH) {
            throw new InvalidArgumentException(sprintf('%s must be less than %s chars.', self::NAME, self::MAX_LENGTH));
        }
    }
}
