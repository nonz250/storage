<?php
declare(strict_types=1);

include_once 'vendor/autoload.php';

/**
 * Load dotenv.
 */
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

/**
 * Load environment.
 */
$env = new Nonz250\Storage\App\Shared\ValueObject\Environment($_ENV['APP_ENV']);

/**
 * Create request.
 */
$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES,
);

/**
 * Setting router.
 */
$responseFactory = new Laminas\Diactoros\ResponseFactory();
$strategy = new League\Route\Strategy\JsonStrategy($responseFactory);
$router = new League\Route\Router();

if (Nonz250\Storage\App\Foundation\App::environment(Nonz250\Storage\App\Shared\ValueObject\Environment::LOCAL)) {
    $router
        ->group('test', static function (League\Route\RouteGroup $router) use ($strategy) {
            $router->get('/', static function (): array {
                return [
                    'message' => 'test',
                ];
            })->setStrategy($strategy);

            $router->get('/hello', static function (): Psr\Http\Message\ResponseInterface {
                $response = new Laminas\Diactoros\Response();
                $response->getBody()->write('<h1>Hello, World!</h1>');
                return $response;
            });

            $router->get('/action', Nonz250\Storage\App\Http\Test\TestAction::class);
        });
}

$response = $router->dispatch($request);

(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);
