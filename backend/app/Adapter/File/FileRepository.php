<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Adapter\File;

use Nonz250\Storage\App\Domain\File\File;
use Nonz250\Storage\App\Domain\File\FileRepositoryInterface;
use Nonz250\Storage\App\Foundation\Model\BindValues;
use Nonz250\Storage\App\Foundation\Repository;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;

final class FileRepository extends Repository implements FileRepositoryInterface
{
    public function create(File $file): void
    {
        $sql = 'INSERT INTO `files` (`id`, `client_id`, `name`, `origin_mimetype`, `thumbnail_mimetype`) VALUES (:id, :client_id, :name, :origin_mimetype, :thumbnail_mimetype)';
        $bindValues = new BindValues();
        $bindValues->bindValue(':id', (string)$file->identifier());
        $bindValues->bindValue(':client_id', (string)$file->clientId());
        $bindValues->bindValue(':name', (string)$file->fileName());
        $bindValues->bindValue(':origin_mimetype', (string)$file->mimeType());
        $bindValues->bindValue(':thumbnail_mimetype', (string)$file->thumbnailMimeType());
        $this->model->insert($sql, $bindValues);
    }

    public function deleteByClientId(ClientId $clientId): void
    {
        $sql = 'DELETE FROM `files` WHERE `files`.`client_id` = :client_id';
        $bindValues = new BindValues();
        $bindValues->bindValue(':client_id', (string)$clientId);
        $this->model->delete($sql, $bindValues);
    }
}
