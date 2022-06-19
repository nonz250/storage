<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\Client\ValueObject;

use InvalidArgumentException;
use Nonz250\Storage\App\Foundation\ValueObject\EmailValue;

final class ClientEmail extends EmailValue
{
    private const NAME = 'email';

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

        try {
            parent::validate($value);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException(sprintf('%s must be email format.', self::NAME));
        }
    }
}
