<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Foundation;

interface RepositoryInterface
{
    public function beginTransaction(): void;

    public function commit(): void;

    public function rollback(): void;
}
