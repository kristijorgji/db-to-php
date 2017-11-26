<?php

namespace kristijorgji\DbToPhp\Console\Commands;

use kristijorgji\DbToPhp\AppInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends Command
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('init')
            ->setDescription('Initialize the application for ' . AppInfo::NAME)
            ->addArgument('path', InputArgument::OPTIONAL, 'Which path should we initialize?')
            ->setHelp(sprintf(
                '%sInitializes the application for %s%s',
                PHP_EOL,
                AppInfo::NAME,
                PHP_EOL
            ));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path');

        if (null === $path) {
            $path = getcwd();
        }

        $path = realpath($path);

        if (!is_writable($path)) {
            throw new \InvalidArgumentException(sprintf(
                'The directory "%s" is not writable',
                $path
            ));
        }

        $fileName = AppInfo::DEFAULT_CONFIG_FILENAME;
        $filePath = $path . DIRECTORY_SEPARATOR . $fileName;

        if (file_exists($filePath)) {
            throw new \InvalidArgumentException(sprintf(
                'The file "%s" already exists',
                $filePath
            ));
        }

        $contents = file_get_contents(basePath(AppInfo::DEFAULT_CONFIG_FILENAME));

        if (false === file_put_contents($filePath, $contents)) {
            throw new \RuntimeException(sprintf(
                'The file "%s" could not be written to',
                $path
            ));
        }

        $output->writeln('<info>created</info> .' . str_replace(getcwd(), '', $filePath));
    }
}