<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Foundation\Exceptions;

use Psr\Http\Message\ResponseInterface;

interface HttpExceptionInterface
{
    public function getStatusCode(): int;

    public function getApiProblemResponse(string $detail = ''): ResponseInterface;
}
