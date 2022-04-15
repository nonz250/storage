<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File;

interface FileRepositoryInterface
{
    public function create(File $file): void;
}
