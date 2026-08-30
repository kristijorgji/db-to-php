<?php declare(strict_types = 1);

namespace kristijorgji\IntegrationTests\MySql\Php;

use kristijorgji\DbToPhp\AppInfo;
use kristijorgji\DbToPhp\FileSystem\FileSystem;
use PHPUnit\Framework\Attributes\DataProvider;
use function array_map;
use function array_values;
use function basePath;
use function file_exists;
use function range;
use function sprintf;

final class PhpGenerateEntitiesTest extends AbstractPhpTestCase
{
    /**     * @param array $config
     */
    #[DataProvider('generateEntitiesProvider')]
    public function testGenerateEntities(array $config, string $expectedOutputDirectory): void
    {
        $this->actualOutputDirectory = $config['entities']['outputDirectory'];

        $command = 'generate:entities';
        $configurationPath = 'tests/integration/MySql/Php/config.php';

        $this->runCommand(
            $this->consoleApp,
            sprintf('%s --configuration=%s', $command, $configurationPath),
        );

        $this->assertFoldersContentMatch($expectedOutputDirectory,  $this->actualOutputDirectory);
    }

    public function testGenerateEntities_default_config(): void
    {
        $expectedConfig = require basePath(AppInfo::DEFAULT_CONFIG_FILENAME);
        $this->actualOutputDirectory = $expectedConfig['entities']['outputDirectory'];

        $command = 'generate:entities';

        $this->runCommand(
            $this->consoleApp,
            sprintf('%s', $command),
        );

        $this->assertFoldersContentMatch(__DIR__ . '/output/entities/expected/', $this->actualOutputDirectory);
    }

    public static function generateEntitiesProvider(): array
    {
        $config = require __DIR__ . '/config.php';
        $expectedOutputDirectory = __DIR__ . '/output/entities/expected/';

        return [
            'output_directory_exists' => array_values(array_map(function () use (&$config, $expectedOutputDirectory) {
                return [
                    $config,
                    $expectedOutputDirectory,
                ];
            }, range(0, 0)))[0],
            'output_directory_doesnt_exists' =>
                array_values(array_map(function () use (&$config, $expectedOutputDirectory) {
                    if (file_exists($config['entities']['outputDirectory'])) {
                        (new FileSystem)->deleteDirectory($config['entities']['outputDirectory']);
                    }
                    return [
                        $config,
                        $expectedOutputDirectory,
                    ];
                }, range(0, 0)))[0],
        ];
    }
}
