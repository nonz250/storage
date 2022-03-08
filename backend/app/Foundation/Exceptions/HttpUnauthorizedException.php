<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Foundation\Exceptions;

use Fig\Http\Message\StatusCodeInterface;
use Throwable;

class HttpUnauthorizedException extends HttpException
{
    public function __construct($message = 'Unauthorized.', $code = 0, Throwable $previous = null)
    {
        parent::__construct(StatusCodeInterface::STATUS_UNAUTHORIZED, $message, $code, $previous);
    }
}
