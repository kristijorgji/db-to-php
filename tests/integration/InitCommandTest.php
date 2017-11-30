<?php

namespace kristijorgji\IntegrationTests;

use kristijorgji\DbToPhp\AppInfo;
use kristijorgji\DbToPhp\Console\DbToPhpApplication;
use kristijorgji\DbToPhp\Db\Adapters\MySql\Exceptions\UnknownMySqlTypeException;
use kristijorgji\DbToPhp\FileSystem\FileSystem;
use kristijorgji\Tests\Helpers\CommandTestCaseHelper;
use kristijorgji\Tests\Helpers\TestCase;
use Symfony\Component\Debug\Exception\FatalErrorException;

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

    /**
     * @var string
     */
    private $command;

    public function setUp()
    {
        $this->fileSystem = new FileSystem();
        $this->consoleApp = new DbToPhpApplication();
        $this->originalCwd = getcwd();
        $this->command = 'init';
    }

    public function tearDown()
    {
        chdir($this->originalCwd);
    }

    public function testInit_without_path()
    {
        chdir(dirname(__FILE__));

        $this->runCommand(
            $this->consoleApp,
            sprintf('%s', $this->command)
        );

        $expectedConfigFilePath = __DIR__ . DIRECTORY_SEPARATOR . AppInfo::DEFAULT_CONFIG_FILENAME;
        $this->assertTrue(
            $this->fileSystem->exists($expectedConfigFilePath)
        );

        unlink($expectedConfigFilePath);
    }

    /**
     * @param bool $deleteConfigAfter
     */
    public function testInit_with_path(bool $deleteConfigAfter = true)
    {
        chdir(__DIR__ . '/../');

        $expectedConfigFilePath = __DIR__ . DIRECTORY_SEPARATOR . AppInfo::DEFAULT_CONFIG_FILENAME;

        $this->runCommand(
            $this->consoleApp,
            sprintf('%s %s', $this->command, 'integration')
        );


        $this->assertTrue(
            $this->fileSystem->exists($expectedConfigFilePath)
        );

        if ($deleteConfigAfter) {
            unlink($expectedConfigFilePath);
        }
    }

    public function testInit_already_exists()
    {
        chdir(dirname(__FILE__));

        $expectedConfigFilePath = __DIR__ . DIRECTORY_SEPARATOR . AppInfo::DEFAULT_CONFIG_FILENAME;
        $this->fileSystem->write($expectedConfigFilePath, self::randomString());

        $output = $this->runCommand(
            $this->consoleApp,
            sprintf('%s', $this->command)
        );

        $this->assertRegexp('#The file \".*\" already exists#', $output);

        unlink($expectedConfigFilePath);
    }

    public function testInit_not_existing_directory()
    {
        chdir(dirname(__FILE__));

        $output = $this->runCommand(
            $this->consoleApp,
            sprintf('%s %s', $this->command, self::randomString() . DIRECTORY_SEPARATOR . self::randomString())
        );

        $this->assertRegexp('#Cannot write \".+\"\. Please check#', $output);
    }
}
