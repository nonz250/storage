<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Foundation\Exceptions;

use Fig\Http\Message\StatusCodeInterface;
use Throwable;

class HttpBadRequestException extends HttpException
{
    public function __construct($message = 'BadRequest.', $code = 0, Throwable $previous = null)
    {
        parent::__construct(StatusCodeInterface::STATUS_BAD_REQUEST, $message, $code, $previous);
    }
}
