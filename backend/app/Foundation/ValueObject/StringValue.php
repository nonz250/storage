<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Foundation\ValueObject;

use Countable;
use InvalidArgumentException;

abstract class StringValue implements Countable
{
    protected string $value;

    abstract public function __construct(string $value);

    public function __toString(): string
    {
        return $this->value;
    }

    public function count(): int
    {
        return mb_strlen($this->value);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * @param string $value
     *
     * @throws InvalidArgumentException
     */
    abstract protected function validate(string $value): void;
}
