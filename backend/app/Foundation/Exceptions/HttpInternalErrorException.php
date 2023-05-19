<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Foundation\Exceptions;

use Fig\Http\Message\StatusCodeInterface;
use Throwable;

final class HttpInternalErrorException extends HttpException
{
    public function __construct(
        Throwable $previous = null,
        string $description = '',
        string $message = 'Internal Server Error.'
    ) {
        parent::__construct(
            StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR,
            $description,
            $message,
            $previous
        );
    }
}
