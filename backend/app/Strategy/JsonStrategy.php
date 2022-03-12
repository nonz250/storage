<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Strategy;

use Crell\ApiProblem\ApiProblem;
use Crell\ApiProblem\HttpConverter;
use Laminas\Diactoros\ResponseFactory;
use League\Container\Exception\NotFoundException;
use League\Route\Http;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class JsonStrategy extends \League\Route\Strategy\JsonStrategy
{
    protected function buildJsonResponseMiddleware(Http\Exception $exception): MiddlewareInterface
    {
        return new class($exception) implements MiddlewareInterface {
            protected Http\Exception $exception;

            public function __construct(Http\Exception $exception)
            {
                $this->exception = $exception;
            }

            public function process(
                ServerRequestInterface $request,
                RequestHandlerInterface $handler
            ): ResponseInterface {
                $detail = '';
                if ($this->exception instanceof NotFoundException) {
                    $detail = 'Nothing route.';
                }
                $responseFactory = new ResponseFactory();
                $converter = new HttpConverter($responseFactory, true);
                $problem = new ApiProblem($this->exception->getMessage());
                $problem
                    ->setTitle($this->exception->getMessage())
                    ->setStatus($this->exception->getStatusCode());
                if ($detail) {
                    $problem->setDetail($detail);
                }
                return $converter->toJsonResponse($problem);
            }
        };
    }
}
