<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Foundation;

use Nonz250\Storage\App\Foundation\Model\Model;

abstract class Repository implements RepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function beginTransaction(): void
    {
        $this->model->beginTransaction();
    }

    public function commit(): void
    {
        $this->model->commit();
    }

    public function rollback(): void
    {
        $this->model->rollBack();
    }
}
