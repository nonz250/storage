<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File;

use Nonz250\Storage\App\Foundation\RepositoryInterface;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;

interface FileRepositoryInterface extends RepositoryInterface
{
    public function create(File $file): void;

    public function delete(File $file): void;

    public function deleteByClientId(ClientId $clientId): void;
}
