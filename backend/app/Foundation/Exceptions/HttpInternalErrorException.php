<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Foundation\Exceptions;

use Fig\Http\Message\StatusCodeInterface;
use Throwable;

final class HttpInternalErrorException extends HttpException
{
    public function __construct($description = '', $message = 'Internal Server Error.', $code = 0, Throwable $previous = null)
    {
        parent::__construct(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR, $description, $message, $code, $previous);
    }
}
