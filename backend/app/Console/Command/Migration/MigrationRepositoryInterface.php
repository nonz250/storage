<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Console\Command\Migration;

interface MigrationRepositoryInterface
{
    public function createMigrateTable(): void;

    public function findLatest(): array;

    public function findByFileName(string $fileName): array;

    public function create(string $fileName, int $step): void;
}
