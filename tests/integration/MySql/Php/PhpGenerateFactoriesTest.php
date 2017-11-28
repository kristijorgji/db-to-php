<?php

namespace kristijorgji\IntegrationTests\MySql\Php;

use DirectoryIterator;
use kristijorgji\DbToPhp\AppInfo;
use kristijorgji\DbToPhp\FileSystem\FileSystem;

class PhpGenerateFactoriesTest extends AbstractPhpTestCase
{
    /**
     * @dataProvider generateFactoriesProvider
     * @param array $config
     * @param string $expectedOutputDirectory
     */
    public function testGenerateFactories(array $config, string $expectedOutputDirectory)
    {
        $this->actualOutputDirectory = $config['factories']['outputDirectory'];

        $command = 'generate:factories';
        $configurationPath = 'tests/integration/MySql/Php/config.php';

        $output = $this->runCommand(
            $this->consoleApp,
            sprintf('%s --configuration=%s', $command, $configurationPath)
        );

        $this->assertFoldersContentMatch($expectedOutputDirectory, $this->actualOutputDirectory);
    }

    public function testGenerateFactories_default_config()
    {
        $expectedConfig = require basePath(AppInfo::DEFAULT_CONFIG_FILENAME);
        $this->actualOutputDirectory = $expectedConfig['factories']['outputDirectory'];

        $command = 'generate:factories';

        $this->runCommand(
            $this->consoleApp,
            sprintf('%s', $command)
        );


        $this->assertFoldersContentMatch(__DIR__ . '/output/factories/expected/', $this->actualOutputDirectory);
    }

    public function generateFactoriesProvider()
    {
        $config = require __DIR__ . '/config.php';
        $expectedOutputDirectory = __DIR__ . '/output/factories/expected/';

        return [
            'output_directory_exists' => array_values(array_map(function() use (&$config, $expectedOutputDirectory) {
                return [$config, $expectedOutputDirectory];
            }, range(0, 0)))[0],
            'output_directory_doesnt_exists' =>
                array_values(array_map(function() use (&$config, $expectedOutputDirectory) {
                    if (file_exists($config['factories']['outputDirectory'])) {
                        (new FileSystem())->deleteDirectory($config['factories']['outputDirectory']);
                    }
                    return [$config, $expectedOutputDirectory];
                }, range(0, 0)))[0]
        ];
    }
}
