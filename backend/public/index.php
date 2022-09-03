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
$container = new League\Container\Container();
$container->add(PDO::class)
    ->addArgument(sprintf(
        'mysql:dbname=%s;host=%s;port=%s',
        Nonz250\Storage\App\Foundation\App::env('DB_NAME'),
        Nonz250\Storage\App\Foundation\App::env('DB_HOST'),
        Nonz250\Storage\App\Foundation\App::env('DB_PORT')
    ))
    ->addArgument(Nonz250\Storage\App\Foundation\App::env('DB_USERNAME'))
    ->addArgument(Nonz250\Storage\App\Foundation\App::env('DB_PASSWORD'));

$container->add(Nonz250\Storage\App\Foundation\Model\Model::class)
    ->addArgument(PDO::class);

$container->add(Psr\Log\LoggerInterface::class, Monolog\Logger::class)
    ->addArgument('storage')
    ->addMethodCall('pushHandler', [
        (new Monolog\Handler\RotatingFileHandler(
            sprintf('%s/../logs/application.log', __DIR__),
            30,
            Nonz250\Storage\App\Foundation\App::environment(Nonz250\Storage\App\Shared\ValueObject\Environment::PRODUCTION)
                ? Monolog\Logger::INFO
                : Monolog\Logger::DEBUG,
        ))->setFormatter(new Monolog\Formatter\JsonFormatter()),
    ]);

$container->add(Nonz250\Storage\App\Http\ParseRequest\ParseRequestMiddleware::class)
    ->addArgument(Psr\Log\LoggerInterface::class);

$container->addServiceProvider(new Nonz250\Storage\App\Provider\ClientServiceProvider);
$container->addServiceProvider(new Nonz250\Storage\App\Provider\FileServiceProvider);

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

// Production routing.
$router
    ->group('/', static function (League\Route\RouteGroup $router) {
        $router->post('/clients', Nonz250\Storage\App\Http\CreateClient\CreateClientAction::class);
        $router->post('/files', Nonz250\Storage\App\Http\UploadFile\UploadFileAction::class);
        $router->delete('/files', Nonz250\Storage\App\Http\DeleteFileByClient\DeleteFileByClientAction::class);
    })
    ->middleware($container->get(Nonz250\Storage\App\Http\Auth\AuthMiddleware::class))
    ->setStrategy($strategy);

$response = $router->dispatch($request);

(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);
