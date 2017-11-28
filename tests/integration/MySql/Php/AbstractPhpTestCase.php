<?php

namespace kristijorgji\IntegrationTests\MySql\Php;

use kristijorgji\DbToPhp\Console\DbToPhpApplication;
use kristijorgji\DbToPhp\FileSystem\FileSystem;
use kristijorgji\Tests\Helpers\CommandTestCaseHelper;
use kristijorgji\Tests\Helpers\MySqlTestCase;

abstract class AbstractPhpTestCase extends MySqlTestCase
{
    use CommandTestCaseHelper;

    /**
     * @var FileSystem
     */
    protected $fileSystem;

    /**
     * @var string
     */
    protected $actualOutputDirectory;

    /**
     * @var DbToPhpApplication
     */
    protected $consoleApp;

    /**
     * @return string
     */
    public static function getDumpPath() : string
    {
        return __DIR__ . '/../test-mysql-db.sql';
    }

    public function setUp()
    {
        $this->fileSystem = new FileSystem();
        $this->consoleApp = new DbToPhpApplication();
    }

    public function tearDown()
    {
        //$this->fileSystem->emptyDirectory($this->actualOutputDirectory);
    }
}
