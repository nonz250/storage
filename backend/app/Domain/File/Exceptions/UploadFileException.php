<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File\Exceptions;

use RuntimeException;
use Throwable;

/**
 * @codeCoverageIgnore
 */
final class UploadFileException extends RuntimeException
{
    public function __construct(string $message = '', ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
