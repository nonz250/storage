<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Http\Auth;

use Nonz250\Storage\App\Domain\DigestAuth\DigestAuth;
use Nonz250\Storage\App\Domain\DigestAuth\DigestAuthInput;
use Nonz250\Storage\App\Foundation\Exceptions\HttpUnauthorizedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $digests = $request->getHeader('Authorization') ?? [];
            if (count($digests) === 0) {
                throw new HttpUnauthorizedException();
            }
        } catch (HttpUnauthorizedException $e) {
            // TODO: ログ記録
            return $e->getApiProblemResponse();
        }

        try {
            try {
                $input = new DigestAuthInput($digests[0], $request->getMethod());
                (new DigestAuth())->process($input);
            } catch (InvalidResponseException $e) {
                // TODO: ログ記録
                throw new HttpUnauthorizedException();
            }
        } catch (HttpUnauthorizedException $e) {
            // TODO: ログ記録
            return $e->getApiProblemResponse();
        }

        return $handler->handle($request);
    }
}
