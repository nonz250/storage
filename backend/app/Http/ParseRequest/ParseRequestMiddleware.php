<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Http\ParseRequest;

use JsonException;
use Nonz250\Storage\App\Foundation\Exceptions\HttpBadRequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

final class ParseRequestMiddleware implements MiddlewareInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            try {
                $contentTypes = $request->getHeader('Content-Type');

                if (count($contentTypes) === 0) {
                    throw new EmptyContentTypeException();
                }

                $contentType = $contentTypes[0];

                if ($contentType !== 'application/json') {
                    throw new InvalidContentTypeException();
                }

                $contents = $request->getBody()->getContents();
                $parsedBody = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
            } catch (EmptyContentTypeException|InvalidContentTypeException $e) {
                $this->logger->error($e);
                throw new HttpBadRequestException($e->getMessage());
            } catch (JsonException $e) {
                $this->logger->error($e);
                throw new HttpBadRequestException('Json syntax error.');
            }
        } catch (HttpBadRequestException $e) {
            return $e->getApiProblemResponse();
        }

        $request = $request->withParsedBody(array_merge($request->getParsedBody(), $parsedBody));

        return $handler->handle($request);
    }
}
