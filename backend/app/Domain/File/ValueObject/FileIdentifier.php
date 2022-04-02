<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File\ValueObject;

use InvalidArgumentException;
use Nonz250\Storage\App\Foundation\Identity\Identifier;
use Nonz250\Storage\App\Foundation\Identity\StringIdentifier;

final class FileIdentifier implements Identifier
{
    use StringIdentifier;

    public function __construct(string $identifier)
    {
        $this->validate($identifier);
        $this->identifier = $identifier;
    }

    private function validate(string $identifier): void
    {
        if (!$this->isValidForUlid($identifier)) {
            throw new InvalidArgumentException(sprintf('%s - ID is invalid.', __CLASS__));
        }
    }
}
