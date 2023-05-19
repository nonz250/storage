<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Http\Auth;

use RuntimeException;
use Throwable;

final class InvalidResponseException extends RuntimeException
{
    public function __construct(string $message = 'Invalid digest response.', ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
