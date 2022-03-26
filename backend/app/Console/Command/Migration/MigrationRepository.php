<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Console\Command\Migration;

use Nonz250\Storage\App\Foundation\App;
use Nonz250\Storage\App\Foundation\Model\BindValues;
use Nonz250\Storage\App\Foundation\Model\Model;

class MigrationRepository implements MigrationRepositoryInterface
{
    private Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function createMigrateTable(): void
    {
        $sql = sprintf('
            CREATE TABLE `%s` (
                `file` VARCHAR (256) NOT NULL DEFAULT \'\' PRIMARY KEY,
                `step` INT NOT NULL DEFAULT 0,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX `idx_step` (`step`)
            );', App::migrationTable());
        $this->model->execute($sql);
    }

    public function findLatest(): array
    {
        $sql = sprintf('SELECT * FROM %s ORDER BY step DESC LIMIT 1', App::migrationTable());
        $migrations = $this->model->select($sql);
        if ($migrations) {
            return $migrations[0];
        }
        return [];
    }

    public function findByFileName(string $fileName): array
    {
        $sql = sprintf('SELECT * FROM %s WHERE file = :file_name', App::migrationTable());
        $bindValues = new BindValues();
        $bindValues->bindValue(':file_name', $fileName);
        $migrations = $this->model->select($sql, $bindValues);
        if ($migrations) {
            return $migrations[0];
        }
        return [];
    }

    public function create(string $fileName, int $step): void
    {
        $sql = sprintf('INSERT INTO `%s` (`file`, `step`) VALUES (:file_name, :step)', App::migrationTable());
        $bindValues = new BindValues();
        $bindValues->bindValue(':file_name', $fileName);
        $bindValues->bindValue(':step', $step);
        $this->model->insert($sql, $bindValues);
    }
}
