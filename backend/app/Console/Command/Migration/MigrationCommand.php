<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Console\Command\Migration;

use Nonz250\Storage\App\Foundation\App;
use Nonz250\Storage\App\Foundation\Model\Model;
use PDO;
use PDOException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class MigrationCommand extends Command
{
    private const MIGRATION_DIRECTORY = 'database/migrations/';

    protected static $defaultName = 'migrate';
    protected static $defaultDescription = 'DB migration for MySQL.';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // モデルの生成
        $dsn = sprintf('mysql:dbname=%s;host=%s;port=%s', App::env('DB_NAME'), App::env('DB_HOST'), App::env('DB_PORT'));
        $model = new Model(new PDO($dsn, App::env('DB_USERNAME'), App::env('DB_PASSWORD')));
        $migrationRepository = new MigrationRepository($model);
        $step = 1;

        try {
            $model->beginTransaction();

            try {
                // `migrations`テーブルの存在チェック
                $latest = $migrationRepository->findLatest();
                // 最新のstepを取得
                $step = array_key_exists('step', $latest) ? $latest['step'] + 1 : $step;
            } catch (PDOException $e) {
                // `migrations`テーブルが無ければ作成する
                $migrationRepository->createMigrateTable();
            }

            // 対象のファイルを全て取得
            $files = glob(self::MIGRATION_DIRECTORY . '*.sql');
            foreach ($files as $file) {
                $fileName = basename($file);
                $migration = $migrationRepository->findByFileName($fileName);
                if ($migration) {
                    continue;
                }

                $output->writeln(sprintf('Migrating %s ...', $fileName));

                $fp = fopen($file, 'rb');
                $sql = fread($fp, filesize($file));
                fclose($fp);

                $model->execute($sql);

                $migrationRepository->create($fileName, $step);

                $output->writeln(sprintf('Migrated %s.', basename($file)));
            }

            $model->commit();
        } catch (PDOException $e) {
            // NOTE: CREATE TABLE など一部のSQLはロールバックできない。
            $output->writeln('Failed migrate. Rollback without CREATE TABLE and others.');
            $model->rollBack();
            throw $e;
        }

        return Command::SUCCESS;
    }
}
