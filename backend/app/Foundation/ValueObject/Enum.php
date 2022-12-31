<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Foundation\ValueObject;

use InvalidArgumentException;
use ReflectionObject;

abstract class Enum
{
    private mixed $value;

    /**
     * Enum constructor.
     *
     * @param mixed $value
     */
    public function __construct(mixed $value)
    {
        $ref = new ReflectionObject($this);
        $constants = $ref->getConstants();

        if (!in_array($value, $constants, true)) {
            throw new InvalidArgumentException();
        }
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->value;
    }

    /**
     * @return mixed
     */
    public function value(): mixed
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function toInt(): int
    {
        return (int)$this->value;
    }
}
