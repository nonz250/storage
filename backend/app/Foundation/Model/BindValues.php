<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Foundation\Model;

use ArrayIterator;
use InvalidArgumentException;
use IteratorAggregate;

final class BindValues implements IteratorAggregate
{
    private array $values = [];

    public function bindValue(string $key, $value): self
    {
        $this->validate($key);
        $this->values[$key] = $value;
        return $this;
    }

    private function validate(string $key): void
    {
        if ($key === '') {
            throw new InvalidArgumentException('Key is required.');
        }
        if (array_key_exists($key, $this->values)) {
            throw new InvalidArgumentException('Key already exists.');
        }
    }

    public function isEmpty(): bool
    {
        return count($this->values) === 0;
    }

    public function toArray(): array
    {
        return $this->values;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->values);
    }
}
