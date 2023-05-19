<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Foundation\Exceptions;

use Fig\Http\Message\StatusCodeInterface;
use Throwable;

final class HttpUnauthorizedException extends HttpException
{
    public function __construct(
        string $description = '',
        Throwable $previous = null,
        string $message = 'Unauthorized.'
    ) {
        parent::__construct(
            StatusCodeInterface::STATUS_UNAUTHORIZED,
            $description,
            $message,
            $previous
        );
    }
}
