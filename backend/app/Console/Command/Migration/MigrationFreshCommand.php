<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Console\Command\Migration;

use Nonz250\Storage\App\Foundation\App;
use Nonz250\Storage\App\Foundation\Model\Model;
use PDO;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class MigrationFreshCommand extends Command
{
    protected static $defaultName = 'migrate:fresh';
    protected static $defaultDescription = 'DB migration fresh for MySQL.';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // モデルの生成
        $dsn = sprintf('mysql:dbname=%s;host=%s;port=%s', App::env('DB_NAME'), App::env('DB_HOST'), App::env('DB_PORT'));
        $model = new Model(new PDO($dsn, App::env('DB_USERNAME'), App::env('DB_PASSWORD')));

        $migrationRepository = new MigrationRepository($model);
        $migrationRepository->fresh(App::env('DB_NAME'));

        if ($application = $this->getApplication()) {
            $migrate = $application->find('migrate');
            $migrate->run(new ArrayInput([]), $output);
        }

        return Command::SUCCESS;
    }
}
