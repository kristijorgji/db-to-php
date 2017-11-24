<?php

namespace kristijorgji\DbToPhp\Console\Commands;

use kristijorgji\DbToPhp\AppInfo;
use kristijorgji\DbToPhp\Config\ConfigFactory;
use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterFactory;
use kristijorgji\DbToPhp\Managers\ManagerContract;
use kristijorgji\DbToPhp\Managers\ManagerFactory;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapperFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
{
    /**
     * @var ConfigFactory
     */
    private $configFactory;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var ManagerContract
     */
    private $manager;

    /**
     * AbstractCommand constructor.
     * @param ConfigFactory $configFactory
     * @param string $name
     */
    public function __construct(ConfigFactory $configFactory, string $name)
    {
        $this->configFactory = $configFactory;
        parent::__construct($name);
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->addOption(
            '--configuration', '-c', InputOption::VALUE_REQUIRED,
            'The configuration file to load'
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function bootstrap(InputInterface $input, OutputInterface $output)
    {
        if ($this->getConfig() === null) {
            $this->loadConfig($input, $output);
        }

        if ($this->manager === null) {
            $this->manager = $this->loadManager();
        }
    }

    /**
     * @return ManagerContract
     */
    protected function getManager() : ManagerContract
    {
        return $this->manager;
    }

    /**
     * @param array $config
     */
    protected function setConfig(array $config)
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

    /**
     * @return ManagerContract
     */
    protected function loadManager() : ManagerContract
    {
        return (new ManagerFactory(
            new DatabaseAdapterFactory(),
            new PhpTypeMapperFactory()
        ))->get($this->getConfig());
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function loadConfig(InputInterface $input, OutputInterface $output)
    {
        $configFilePath = $this->locateConfigFile($input);
        $output->writeln('<info>using config file</info> .' . str_replace(getcwd(), '', realpath($configFilePath)));
        $this->setConfig($this->configFactory->get($configFilePath));
    }

    /**
     * Returns config file path
     *
     * @param InputInterface $input
     * @return string
     */
    protected function locateConfigFile(InputInterface $input) : string
    {
        $configFile = $input->getOption('configuration');
        if (null === $configFile || false === $configFile) {
            return $this->locateDefaultConfigFile();
        }

        return getcwd() . DIRECTORY_SEPARATOR . $configFile;
    }

    /**
     * @return string
     */
    protected function locateDefaultConfigFile() : string
    {
        $cwd = getcwd();
        return $cwd . DIRECTORY_SEPARATOR . AppInfo::DEFAULT_CONFIG_FILENAME;
    }
}
