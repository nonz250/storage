<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File;

use Nonz250\Storage\App\Foundation\RepositoryInterface;

interface FileRepositoryInterface extends RepositoryInterface
{
    public function create(File $file): void;
}
