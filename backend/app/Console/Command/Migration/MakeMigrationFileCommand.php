<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Console\Command\Migration;

use DateTimeImmutable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class MakeMigrationFileCommand extends Command
{
    private const FILE_NAME_ARGUMENT = 'file_name';

    private const FILE_DATE_FORMAT = 'YmdHisv';

    private const FILE_EXTENSION = 'sql';

    private const MIGRATION_DIRECTORY = 'database/migrations/';

    protected static $defaultName = 'make:migration';

    protected static $defaultDescription = 'Make migration file.';

    protected function configure(): void
    {
        $this
            ->addArgument(self::FILE_NAME_ARGUMENT, InputArgument::REQUIRED, 'Migration file name.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = new DateTimeImmutable();
        $inputFileName = $input->getArgument(self::FILE_NAME_ARGUMENT);
        $migrationFileName = self::MIGRATION_DIRECTORY . sprintf('%s_%s.%s', $now->format(self::FILE_DATE_FORMAT), $inputFileName, self::FILE_EXTENSION);
        $output->writeln(sprintf('Creating %s ...', $migrationFileName));

        if (!touch($migrationFileName)) {
            $output->writeln(sprintf('Failed to create %s .', $migrationFileName));
            return Command::FAILURE;
        }

        $output->writeln(sprintf('Successfully created %s. ', $migrationFileName));
        return Command::SUCCESS;
    }
}
