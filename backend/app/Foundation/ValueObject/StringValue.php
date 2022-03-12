<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Foundation\ValueObject;

use InvalidArgumentException;
use Stringable;

abstract class StringValue implements Stringable
{
    protected string $value;

    abstract public function __construct(string $value);

    public function isEmpty(): bool
    {
        return true;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @throws InvalidArgumentException
     */
    abstract protected function validate(string $value): void;
}
