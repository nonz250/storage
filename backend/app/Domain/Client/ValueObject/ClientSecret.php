<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\Client\ValueObject;

use InvalidArgumentException;
use Nonz250\Storage\App\Foundation\ValueObject\StringValue;

final class ClientSecret extends StringValue
{
    public const LENGTH = 64;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    protected function validate(string $value): void
    {
        if ($value === '') {
            throw new InvalidArgumentException(sprintf('%s is required.', __CLASS__));
        }
        if (!ctype_print($value)) {
            throw new InvalidArgumentException(sprintf('%s must be hex character only.', __CLASS__));
        }
        if (mb_strlen($value) !== self::LENGTH) {
            throw new InvalidArgumentException(
                sprintf('%s must be %s chars.', __CLASS__, self::LENGTH)
            );
        }
    }
}
