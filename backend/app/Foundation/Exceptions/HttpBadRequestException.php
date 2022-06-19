<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Foundation\Exceptions;

use Fig\Http\Message\StatusCodeInterface;
use Throwable;

final class HttpBadRequestException extends HttpException
{
    public function __construct($description = '', $message = 'BadRequest.', $code = 0, Throwable $previous = null)
    {
        parent::__construct(StatusCodeInterface::STATUS_BAD_REQUEST, $description, $message, $code, $previous);
    }
}
