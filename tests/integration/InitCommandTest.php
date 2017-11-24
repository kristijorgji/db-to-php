<?php

namespace kristijorgji\IntegrationTests;

use kristijorgji\DbToPhp\AppInfo;
use kristijorgji\DbToPhp\Console\DbToPhpApplication;
use kristijorgji\DbToPhp\FileSystem\FileSystem;
use kristijorgji\Tests\Helpers\CommandTestCaseHelper;
use kristijorgji\Tests\Helpers\TestCase;

class InitCommandTest extends TestCase
{
    use CommandTestCaseHelper;

    /**
     * @var FileSystem
     */
    private $fileSystem;

    /**
     * @var string
     */
    private $originalCwd;

    /**
     * @var DbToPhpApplication
     */
    private $consoleApp;

    public function setUp()
    {
        $this->fileSystem = new FileSystem();
        $this->consoleApp = new DbToPhpApplication();
        $this->originalCwd = getcwd();
    }

    public function tearDown()
    {
        chdir($this->originalCwd);
    }

    public function testInit_without_path()
    {
        chdir(dirname(__FILE__));

        $command = 'init';

        $this->runCommand(
            $this->consoleApp,
            sprintf('%s', $command)
        );

        $expectedConfigFilePath = __DIR__ . DIRECTORY_SEPARATOR . AppInfo::DEFAULT_CONFIG_FILENAME;
        $this->assertTrue(
            $this->fileSystem->exists($expectedConfigFilePath)
        );

        unlink($expectedConfigFilePath);
    }

    public function testInit_with_path()
    {
        chdir(__DIR__ . '/../');

        $command = 'init';

        $expectedConfigFilePath = __DIR__ . DIRECTORY_SEPARATOR . AppInfo::DEFAULT_CONFIG_FILENAME;

        $this->runCommand(
            $this->consoleApp,
            sprintf('%s %s', $command, 'integration')
        );


        $this->assertTrue(
            $this->fileSystem->exists($expectedConfigFilePath)
        );

        unlink($expectedConfigFilePath);
    }
}
