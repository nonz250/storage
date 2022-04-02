<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File\ValueObject;

use InvalidArgumentException;

final class Image
{
    public const MAX_LENGTH = 10 * 1000 * 1000; // 10MB
    private const NAME = 'file';

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
            throw new InvalidArgumentException(sprintf('%s must be less than %s byte.', self::NAME, self::MAX_LENGTH));
        }
    }
}
