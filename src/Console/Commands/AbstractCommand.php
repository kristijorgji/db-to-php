<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Console\Commands;

use kristijorgji\DbToPhp\AppInfo;
use kristijorgji\DbToPhp\Config\ConfigFactory;
use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterFactory;
use kristijorgji\DbToPhp\Managers\GenerateResponse;
use kristijorgji\DbToPhp\Managers\ManagerContract;
use kristijorgji\DbToPhp\Managers\ManagerFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function getcwd;
use function realpath;
use function sprintf;
use function str_replace;
use const DIRECTORY_SEPARATOR;

abstract class AbstractCommand extends Command
{
    protected ?array $config = null;
    private ?ManagerContract $manager = null;

    /**
     * AbstractCommand constructor.
     */
    public function __construct(private ConfigFactory $configFactory, string $name)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->addOption(
            '--configuration',
            '-c',
            InputOption::VALUE_REQUIRED,
            'The configuration file to load',
        );
    }

    public function bootstrap(InputInterface $input, OutputInterface $output): void
    {
        if ($this->getConfig() === null) {
            $this->loadConfig($input, $output);
        }

        if ($this->manager === null) {
            $this->manager = $this->loadManager();
        }
    }

    protected function getManager() : ManagerContract
    {
        return $this->manager;
    }

    protected function setConfig(array $config): void
    {
        $this->config = $config;
    }

    /**
     * @return array|null
     */
    protected function getConfig() : ?array
    {
        return $this->config;
    }

    protected function loadManager() : ManagerContract
    {
        return (new ManagerFactory(
            new DatabaseAdapterFactory,
        ))->get($this->getConfig());
    }

    protected function loadConfig(InputInterface $input, OutputInterface $output): void
    {
        $configFilePath = $this->locateConfigFile($input);
        $output->writeln('<info>using config file</info> .' . str_replace(getcwd(), '', realpath($configFilePath)));
        $this->setConfig($this->configFactory->get($configFilePath));
    }

    /**
     * Returns config file path
     *
     */
    protected function locateConfigFile(InputInterface $input) : string
    {
        $configFile = $input->getOption('configuration');
        if ($configFile === null || $configFile === false) {
            return $this->locateDefaultConfigFile();
        }

        return getcwd() . DIRECTORY_SEPARATOR . $configFile;
    }

    protected function locateDefaultConfigFile() : string
    {
        $cwd = getcwd();
        return $cwd . DIRECTORY_SEPARATOR . AppInfo::DEFAULT_CONFIG_FILENAME;
    }

    protected function outputGenerationResult(OutputInterface $output, GenerateResponse $generateResponse): void
    {
        $output->writeln('');
        foreach ($generateResponse->getPaths() as $path) {
            $output->writeln(
                sprintf('Created: %s', $path),
            );
        }
    }
}
