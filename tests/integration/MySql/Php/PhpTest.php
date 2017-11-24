<?php

namespace kristijorgji\IntegrationTests\MySql\Php;

use DirectoryIterator;
use kristijorgji\DbToPhp\AppInfo;
use kristijorgji\DbToPhp\Console\DbToPhpApplication;
use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterFactory;
use kristijorgji\DbToPhp\FileSystem\Exceptions\FileSystemException;
use kristijorgji\DbToPhp\FileSystem\FileSystem;
use kristijorgji\DbToPhp\Managers\ManagerFactory;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapperFactory;
use kristijorgji\Tests\Helpers\CommandTestCaseHelper;
use kristijorgji\Tests\Helpers\MySqlTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;

class PhpTest extends MySqlTestCase
{
    use CommandTestCaseHelper;

    /**
     * @var FileSystem
     */
    private $fileSystem;

    /**
     * @var string
     */
    private $actualOutputDirectory;

    /**
     * @var DbToPhpApplication
     */
    private $consoleApp;

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
        $this->fileSystem->emptyDirectory($this->actualOutputDirectory);
    }

    /**
     * @dataProvider generateEntitiesProvider
     * @param array $config
     * @param string $expectedOutputDirectory
     */
    public function testGenerateEntities(array $config, string $expectedOutputDirectory)
    {
        $this->actualOutputDirectory = $config['entities']['outputDirectory'];

        $command = 'generate:entities';
        $configurationPath = 'tests/integration/MySql/Php/config.php';

        $output = $this->runCommand(
            $this->consoleApp,
            sprintf('%s --configuration=%s', $command, $configurationPath)
        );

        $this->assertFoldersMatch($expectedOutputDirectory);
    }

    public function testGenerateEntities_default_config()
    {
        $expectedConfig = require basePath(AppInfo::DEFAULT_CONFIG_FILENAME);
        $this->actualOutputDirectory = $expectedConfig['entities']['outputDirectory'];

        $command = 'generate:entities';

        $this->runCommand(
            $this->consoleApp,
            sprintf('%s', $command)
        );


        $this->assertFoldersMatch(__DIR__ . '/output/expected/');
      }

    private function assertFoldersMatch(string $expectedOutputDirectory)
    {
        $expectedEntityClassNames = [
            'BinariusEntity.php',
            'SuperEntity.php',
            'Test2Entity.php',
        ];

        foreach (new DirectoryIterator($this->actualOutputDirectory) as $fileInfo) {
            if(!$fileInfo->isDot()) {
                $this->assertTrue(
                    in_array($fileInfo->getFilename(), $expectedEntityClassNames),
                    sprintf('File %s was not expected!', $fileInfo->getFilename())
                );
            }
        }

        foreach ($expectedEntityClassNames as $expectedEntityClassName) {
            $this->assertEquals(
                file_get_contents($expectedOutputDirectory . '/' . $expectedEntityClassName),
                file_get_contents($this->actualOutputDirectory . '/' . $expectedEntityClassName)
            );
        }
    }

    public function generateEntitiesProvider()
    {
        $config = require __DIR__ . '/config.php';
        $expectedOutputDirectory = __DIR__ . '/output/expected/';

        return [
            'output_directory_exists' => array_values(array_map(function() use (&$config, $expectedOutputDirectory) {
                return [$config, $expectedOutputDirectory];
            }, range(0, 0)))[0],
            'output_directory_doesnt_exists' =>
                array_values(array_map(function() use (&$config, $expectedOutputDirectory) {
                    if (file_exists($config['entities']['outputDirectory'])) {
                        (new FileSystem())->deleteDirectory($config['entities']['outputDirectory']);
                    }
                    return [$config, $expectedOutputDirectory];
                }, range(0, 0)))[0]
        ];
    }
}
