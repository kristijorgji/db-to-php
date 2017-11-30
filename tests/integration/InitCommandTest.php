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

    /**
     * @param bool $deleteConfigAfter
     */
    public function testInit_with_path(bool $deleteConfigAfter = true)
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

        if ($deleteConfigAfter) {
            unlink($expectedConfigFilePath);
        }
    }

    public function testInit_already_exists()
    {
        chdir(dirname(__FILE__));

        $expectedConfigFilePath = __DIR__ . DIRECTORY_SEPARATOR . AppInfo::DEFAULT_CONFIG_FILENAME;
        $this->fileSystem->write($expectedConfigFilePath, self::randomString());

        $command = 'init';


        $output = $this->runCommand(
            $this->consoleApp,
            sprintf('%s', $command)
        );

        $this->assertRegexp('#The file \".*\" already exists#', $output);

        unlink($expectedConfigFilePath);
    }
}
