<?php
declare(strict_types=1);

include_once '../vendor/autoload.php';

/**
 * Load dotenv.
 */
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
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
 * Dependency injection.
 */
$container = Nonz250\Storage\App\Adapter\Bootstrap\Bootstrap::settingContainers();

/**
 * Setting router.
 */
$responseFactory = new Laminas\Diactoros\ResponseFactory();
$strategy = new Nonz250\Storage\App\Strategy\JsonStrategy($responseFactory);
$strategy->setContainer($container);
$router = new League\Route\Router();
$router->middleware($container->get(Nonz250\Storage\App\Http\ParseRequest\ParseRequestMiddleware::class));

// Local routing for testing.
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

try {
    // Production routing.
    $router
        ->group('/', static function (League\Route\RouteGroup $router) {
            $router->post('/clients', Nonz250\Storage\App\Http\CreateClient\CreateClientAction::class);
            $router->post('/files', Nonz250\Storage\App\Http\UploadFile\UploadFileAction::class);
            $router->delete('/files', Nonz250\Storage\App\Http\DeleteFileByClient\DeleteFileByClientAction::class);
            $router->delete('/files/{fileIdentifier}', Nonz250\Storage\App\Http\DeleteFileById\DeleteFileByIdAction::class);
        })
        ->middleware($container->get(Nonz250\Storage\App\Http\Auth\AuthMiddleware::class))
        ->setStrategy($strategy);

    $response = $router->dispatch($request);

    (new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);

} catch (PDOException|Throwable $e) {
    $internalServerError = new Nonz250\Storage\App\Foundation\Exceptions\HttpInternalErrorException($e);
    header('Content-Type: application/json');
    http_response_code($internalServerError->getStatusCode());
    echo $internalServerError
        ->getApiProblemResponse()
        ->getBody()
        ->getContents();
    exit();
}
