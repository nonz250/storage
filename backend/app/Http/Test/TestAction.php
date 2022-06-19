<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Http\Test;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;

final class TestAction
{
    /**
     * @return ResponseInterface
     */
    public function __invoke(): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write('test');
        return $response;
    }
}
