<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Foundation\Exceptions;

use Crell\ApiProblem\ApiProblem;
use Crell\ApiProblem\HttpConverter;
use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Throwable;

class HttpException extends RuntimeException implements HttpExceptionInterface
{
    private int $statusCode;

    public function __construct(int $statusCode = StatusCodeInterface::STATUS_OK, $message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getApiProblemResponse(string $detail = ''): ResponseInterface
    {
        $responseFactory = new ResponseFactory();
        $converter = new HttpConverter($responseFactory, true);
        $problem = new ApiProblem($this->getMessage());
        $problem
            ->setStatus($this->getStatusCode())
            ->setDetail($detail);
        return $converter->toJsonResponse($problem);
    }
}
