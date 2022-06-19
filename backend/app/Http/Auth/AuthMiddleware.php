<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Http\Auth;

use InvalidArgumentException;
use Nonz250\Storage\App\Domain\Auth\Command\DigestAuth\DigestAuthInput;
use Nonz250\Storage\App\Domain\Auth\Command\DigestAuth\DigestAuthInterface;
use Nonz250\Storage\App\Foundation\App;
use Nonz250\Storage\App\Foundation\Exceptions\DataNotFoundException;
use Nonz250\Storage\App\Foundation\Exceptions\HttpBadRequestException;
use Nonz250\Storage\App\Foundation\Exceptions\HttpException;
use Nonz250\Storage\App\Foundation\Exceptions\HttpInternalErrorException;
use Nonz250\Storage\App\Foundation\Exceptions\HttpUnauthorizedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

final class AuthMiddleware implements MiddlewareInterface
{
    private LoggerInterface $logger;

    private DigestAuthInterface $digestAuth;

    public function __construct(
        LoggerInterface $logger,
        DigestAuthInterface $digestAuth
    ) {
        $this->logger = $logger;
        $this->digestAuth = $digestAuth;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $digests = $request->getHeader('Authorization') ?? [];

            if (count($digests) === 0) {
                throw new HttpUnauthorizedException('Please set `Authorization` header.');
            }
        } catch (HttpUnauthorizedException $e) {
            $this->logger->error($e);
            return $e->getApiProblemResponse();
        }

        try {
            try {
                $input = new DigestAuthInput($digests[0], $request->getMethod(), App::env('DIGEST_NONCE'));
                $this->digestAuth->process($input);
            } catch (InvalidArgumentException|DataNotFoundException $e) {
                $this->logger->error($e);
                throw new HttpBadRequestException($e->getMessage());
            } catch (InvalidResponseException $e) {
                $this->logger->error($e);
                throw new HttpUnauthorizedException('Please check user.');
            } catch (Throwable $e) {
                $this->logger->error($e);
                throw new HttpInternalErrorException($e->getMessage());
            }
        } catch (HttpException $e) {
            $this->logger->error($e);
            return $e->getApiProblemResponse();
        }

        $request = $request->withParsedBody(array_merge($request->getParsedBody(), [
            'client_id' => $input->userName(),
        ]));

        return $handler->handle($request);
    }
}
