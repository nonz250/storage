<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Console\Command\Client;

use Nonz250\Storage\App\Domain\Client\ValueObject\AppName;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientEmail;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientId;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientSecret;
use Nonz250\Storage\App\Foundation\App;
use Nonz250\Storage\App\Foundation\Model\BindValues;
use Nonz250\Storage\App\Foundation\Model\Model;
use PDO;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateClientCommand extends Command
{
    private const APP_NAME_ARGUMENT = 'app_name';
    private const EMAIL_ARGUMENT = 'email';

    protected static $defaultName = 'make:client';
    protected static $defaultDescription = 'Make client.';

    protected function configure(): void
    {
        $this
            ->addArgument(self::APP_NAME_ARGUMENT, InputArgument::REQUIRED, 'Client\'s app name.')
            ->addArgument(self::EMAIL_ARGUMENT, InputArgument::REQUIRED, 'Client\'s email.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // モデルの生成
        $dsn = sprintf('mysql:dbname=%s;host=%s;port=%s', App::env('DB_NAME'), App::env('DB_HOST'), App::env('DB_PORT'));
        $model = new Model(new PDO($dsn, App::env('DB_USERNAME'), App::env('DB_PASSWORD')));

        // 値の初期化
        $clientId = ClientId::generate();
        $clientSecret = ClientSecret::generate();
        $appName = new AppName((string)($input->getArgument(self::APP_NAME_ARGUMENT) ?? ''));
        $email = new ClientEmail((string)($input->getArgument(self::EMAIL_ARGUMENT) ?? ''));

        // 永続化
        $sql = 'INSERT INTO `clients` (`id`, `secret`, `app_name`, `email`) VALUE (:client_id, :client_secret, :app_name, :email)';
        $bindValues = new BindValues();
        $bindValues->bindValue(':client_id', (string)$clientId);
        $bindValues->bindValue(':client_secret', (string)$clientSecret);
        $bindValues->bindValue(':app_name', (string)$appName);
        $bindValues->bindValue(':email', (string)$email);
        $model->insert($sql, $bindValues);

        // 作成したクライアントを表示
        $output->writeln('Client ID     : ' . $clientId);
        $output->writeln('Client secret : ' . $clientSecret);
        $output->writeln('App name      : ' . $appName);
        $output->writeln('Email         : ' . $email);

        return Command::SUCCESS;
    }
}
