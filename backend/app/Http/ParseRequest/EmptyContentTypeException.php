<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Http\ParseRequest;

use RuntimeException;
use Throwable;

final class EmptyContentTypeException extends RuntimeException
{
    public function __construct($message = 'Content-Type is empty.', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
