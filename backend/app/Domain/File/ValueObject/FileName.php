<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File\ValueObject;

use InvalidArgumentException;

final class FileName
{
    public const MAX_LENGTH = 200;
    private const NAME = 'fileName';

    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function validate(string $value): void
    {
        if ($value === '') {
            throw new InvalidArgumentException(sprintf('%s is required.', self::NAME));
        }
        if (mb_strlen($value) > self::MAX_LENGTH) {
            throw new InvalidArgumentException(sprintf('%s must be less than %s chars.', self::NAME, self::MAX_LENGTH));
        }
    }
}
