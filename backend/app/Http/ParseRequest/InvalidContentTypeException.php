<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Http\ParseRequest;

use RuntimeException;
use Throwable;

class InvalidContentTypeException extends RuntimeException
{
    public function __construct($message = 'Invalid Content-Type.', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
