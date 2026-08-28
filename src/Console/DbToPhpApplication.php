<?php declare(strict_types = 1);

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
use function sprintf;
use function str_repeat;
use function strlen;

class DbToPhpApplication extends Application
{
    public function __construct(string $version = AppInfo::VERSION)
    {
        $configFactory = new ConfigFactory(new FileSystem);

        parent::__construct(sprintf('%s by Kristi Jorgji - %s', AppInfo::NAME, $version));
        $this->addCommands([
           new InitCommand('Initialize the application'),
           new GenerateEntitiesCommand($configFactory, 'Generate entities'),
           new GenerateFactoriesCommand($configFactory, 'Generate entity factories'),
        ]);
    }

    /**
     * @return int 0 if everything went fine, or an error code
     */
    public function doRun(InputInterface $input, OutputInterface $output): int
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
