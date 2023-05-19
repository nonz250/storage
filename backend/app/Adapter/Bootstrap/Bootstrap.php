<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Adapter\Bootstrap;

use League\Container\Container;
use League\Container\DefinitionContainerInterface;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Nonz250\Storage\App\Foundation\App;
use Nonz250\Storage\App\Foundation\Model\Model;
use Nonz250\Storage\App\Http\ParseRequest\ParseRequestMiddleware;
use Nonz250\Storage\App\Provider\ClientServiceProvider;
use Nonz250\Storage\App\Provider\FileServiceProvider;
use Nonz250\Storage\App\Shared\ValueObject\Environment;
use PDO;
use Psr\Log\LoggerInterface;

/**
 * @codeCoverageIgnore
 */
final class Bootstrap
{
    public static function settingContainers(): DefinitionContainerInterface
    {
        $container = new Container();

        $container->addShared(PDO::class)
            ->addArgument(sprintf(
                'mysql:dbname=%s;host=%s;port=%s',
                App::env('DB_NAME'),
                App::env('DB_HOST'),
                App::env('DB_PORT')
            ))
            ->addArgument(App::env('DB_USERNAME'))
            ->addArgument(App::env('DB_PASSWORD'));

        $container->addShared(Model::class)
            ->addArgument(PDO::class);

        $container->addShared(LoggerInterface::class, Logger::class)
            ->addArgument('storage')
            ->addMethodCall('pushHandler', [
                (new RotatingFileHandler(
                    sprintf('%s/../../../logs/application.log', __DIR__),
                    30,
                    App::environment(Environment::PRODUCTION)
                        ? Logger::INFO
                        : Logger::DEBUG,
                ))->setFormatter(new JsonFormatter()),
            ]);

        $container->addShared(ParseRequestMiddleware::class)
            ->addArgument(LoggerInterface::class);

        $container->addServiceProvider(new ClientServiceProvider());
        $container->addServiceProvider(new FileServiceProvider());

        return $container;
    }
}
