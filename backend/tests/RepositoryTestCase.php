<?php
declare(strict_types=1);

namespace Tests;

use Nonz250\Storage\App\Adapter\Bootstrap\Bootstrap;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Throwable;

abstract class RepositoryTestCase extends TestCase
{
    protected function make(string $id)
    {
        try {
            return Bootstrap::settingContainers()->get($id);
        } catch (Throwable $e) {
            throw new RuntimeException('Failed to get container.', $e->getCode(), $e);
        }
    }
}
