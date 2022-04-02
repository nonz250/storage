<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Foundation\Identity;

interface Identifier
{
    /**
     * @param Identifier $id
     * @return bool
     */
    public function equals(self $id): bool;

    /**
     * @return string
     */
    public function __toString(): string;
}
