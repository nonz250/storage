<?php
declare(strict_types=1);

namespace Tests;

use Nonz250\Storage\App\Adapter\Bootstrap\Bootstrap;
use RuntimeException;
use Throwable;

trait RepositoryTestTrait
{
    protected function make(string $id)
    {
        try {
            if ($this->container === null) {
                $this->container = Bootstrap::settingContainers();
            }
            return $this->container->get($id);
        } catch (Throwable $e) {
            throw new RuntimeException('Failed to get container.', $e->getCode(), $e);
        }
    }
}
