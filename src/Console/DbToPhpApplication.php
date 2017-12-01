<?php

namespace kristijorgji\DbToPhp\Console;

use kristijorgji\DbToPhp\AppInfo;
use kristijorgji\DbToPhp\Config\ConfigFactory;
use kristijorgji\DbToPhp\Console\Commands\GenerateEntitiesCommand;
use kristijorgji\DbToPhp\Console\Commands\GenerateFactoriesCommand;
use kristijorgji\DbToPhp\Console\Commands\InitCommand;
use kristijorgji\DbToPhp\FileSystem\FileSystem;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DbToPhpApplication extends Application
{
    /**
     * @param string $version
     */
    public function __construct($version = AppInfo::VERSION)
    {
        $configFactory = new ConfigFactory(new FileSystem());

        parent::__construct(sprintf('%s by Kristi Jorgji - %s', AppInfo::NAME, $version));
        $this->addCommands([
           new InitCommand('Initialize the application'),
           new GenerateEntitiesCommand($configFactory, 'Generate entities'),
           new GenerateFactoriesCommand($configFactory, 'Generate entity factories'),
        ]);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int 0 if everything went fine, or an error code
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        if ($input->hasParameterOption(['--help', '-h']) === false && $input->getFirstArgument() !== null) {
            $output->writeln(str_repeat('-', strlen($this->getLongVersion())));
            $output->writeln($this->getLongVersion());
            $output->writeln(str_repeat('-', strlen($this->getLongVersion())));
            $output->writeln('');
        }

        return parent::doRun($input, $output);
    }
}
