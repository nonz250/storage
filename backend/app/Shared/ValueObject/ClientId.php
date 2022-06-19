<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Shared\ValueObject;

use Exception;
use InvalidArgumentException;
use Nonz250\Storage\App\Foundation\ValueObject\StringValue;
use RuntimeException;

final class ClientId extends StringValue
{
    public const LENGTH = 32;

    public const NAME = 'Username';

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    public static function generate(): self
    {
        try {
            $randoms = bin2hex(random_bytes(self::LENGTH / 2));
            // @codeCoverageIgnoreStart
        } catch (Exception $e) {
            throw new RuntimeException('An appropriate source of randomness cannot be found.', 0, $e);
        }
        // @codeCoverageIgnoreEnd
        return new self($randoms);
    }

    protected function validate(string $value): void
    {
        if ($value === '') {
            throw new InvalidArgumentException(sprintf('%s is required.', self::NAME));
        }

        if (!ctype_xdigit($value)) {
            throw new InvalidArgumentException(sprintf('%s must be hex character only.', self::NAME));
        }

        if (mb_strlen($value) !== self::LENGTH) {
            throw new InvalidArgumentException(
                sprintf('%s must be %s chars.', __CLASS__, self::LENGTH)
            );
        }
    }
}
