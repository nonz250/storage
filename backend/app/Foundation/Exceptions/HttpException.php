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

final class HttpException extends RuntimeException implements HttpExceptionInterface
{
    private int $statusCode;

    private string $description;

    public function __construct(
        int $statusCode = StatusCodeInterface::STATUS_OK,
        $description = '',
        $message = '',
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->statusCode = $statusCode;
        $this->description = $description;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getApiProblemResponse(): ResponseInterface
    {
        $responseFactory = new ResponseFactory();
        $converter = new HttpConverter($responseFactory, true);
        $problem = new ApiProblem($this->getMessage());
        $problem
            ->setStatus($this->getStatusCode())
            ->setDetail($this->description);
        return $converter->toJsonResponse($problem);
    }
}
