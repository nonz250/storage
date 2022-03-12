<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Foundation\ValueObject;

use InvalidArgumentException;

abstract class EmailValue extends StringValue
{
    protected function validate(string $value): void
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException(sprintf('%s must be email format.', __CLASS__));
        }
    }
}
