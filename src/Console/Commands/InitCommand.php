<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Console\Commands;

use InvalidArgumentException;
use kristijorgji\DbToPhp\AppInfo;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function basePath;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function getcwd;
use function realpath;
use function sprintf;
use function str_replace;
use const DIRECTORY_SEPARATOR;
use const PHP_EOL;

class InitCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('init')
            ->setDescription('Initialize the application for ' . AppInfo::NAME)
            ->addArgument('path', InputArgument::OPTIONAL, 'Which path should we initialize?')
            ->setHelp(sprintf(
                '%sInitializes the application for %s%s',
                PHP_EOL,
                AppInfo::NAME,
                PHP_EOL,
            ));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('path');

        $path ??= getcwd();

        $realPath = realpath($path);

        $fileName = AppInfo::DEFAULT_CONFIG_FILENAME;
        $filePath = $realPath . DIRECTORY_SEPARATOR . $fileName;

        if (file_exists($filePath)) {
            throw new InvalidArgumentException(sprintf(
                'The file "%s" already exists',
                $filePath,
            ));
        }

        $contents = file_get_contents(basePath(AppInfo::DEFAULT_CONFIG_FILENAME));

        $wasWritten = @file_put_contents($filePath, $contents);

        if (!$wasWritten) {
            throw new RuntimeException(sprintf(
                'Cannot write "%s". Please check that the path exists'
                .' and if the folder is writable.',
                $path,
            ));
        }

        $output->writeln('<info>created</info> .' . str_replace(getcwd(), '', $filePath));

        return 0;
    }
}
