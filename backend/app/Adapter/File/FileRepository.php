<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Adapter\File;

use Nonz250\Storage\App\Domain\File\File;
use Nonz250\Storage\App\Domain\File\FileRepositoryInterface;
use Nonz250\Storage\App\Foundation\Model\BindValues;
use Nonz250\Storage\App\Foundation\Model\Model;

final class FileRepository implements FileRepositoryInterface
{
    private Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create(File $file): void
    {
        $sql = 'INSERT INTO `files` (`id`, `client_id`, `name`, `mimetype`) VALUES (:id, :client_id, :name, :mimetype)';
        $bindValues = new BindValues();
        $bindValues->bindValue(':id', (string)$file->identifier());
        $bindValues->bindValue(':client_id', (string)$file->clientId());
        $bindValues->bindValue(':name', (string)$file->fileName());
        $bindValues->bindValue(':mimetype', (string)$file->mimeType());
        $this->model->insert($sql, $bindValues);
    }
}