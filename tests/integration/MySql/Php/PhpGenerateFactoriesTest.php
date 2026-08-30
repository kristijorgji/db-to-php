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

final class PhpGenerateFactoriesTest extends AbstractPhpTestCase
{
    /**     * @param array $config
     */
    #[DataProvider('generateFactoriesProvider')]
    public function testGenerateFactories(array $config, string $expectedOutputDirectory): void
    {
        $this->actualOutputDirectory = $config['factories']['outputDirectory'];

        $command = 'generate:factories';
        $configurationPath = 'tests/integration/MySql/Php/config.php';

        $this->runCommand(
            $this->consoleApp,
            sprintf('%s --configuration=%s', $command, $configurationPath),
        );

        $this->assertFoldersContentMatch($expectedOutputDirectory, $this->actualOutputDirectory);
    }

    public function testGenerateFactories_default_config(): void
    {
        $expectedConfig = require basePath(AppInfo::DEFAULT_CONFIG_FILENAME);
        $this->actualOutputDirectory = $expectedConfig['factories']['outputDirectory'];

        $command = 'generate:factories';

        $this->runCommand(
            $this->consoleApp,
            sprintf('%s', $command),
        );

        $this->assertFoldersContentMatch(__DIR__ . '/output/factories/expected/', $this->actualOutputDirectory);
    }

    public static function generateFactoriesProvider(): array
    {
        $config = require __DIR__ . '/config.php';
        $expectedOutputDirectory = __DIR__ . '/output/factories/expected/';

        return [
            'output_directory_exists' => array_values(array_map(function () use (&$config, $expectedOutputDirectory) {
                return [
                    $config,
                    $expectedOutputDirectory,
                ];
            }, range(0, 0)))[0],
            'output_directory_doesnt_exists' =>
                array_values(array_map(function () use (&$config, $expectedOutputDirectory) {
                    if (file_exists($config['factories']['outputDirectory'])) {
                        (new FileSystem)->deleteDirectory($config['factories']['outputDirectory']);
                    }
                    return [
                        $config,
                        $expectedOutputDirectory,
                    ];
                }, range(0, 0)))[0],
        ];
    }
}
