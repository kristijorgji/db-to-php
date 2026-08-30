<?php declare(strict_types = 1);

namespace kristijorgji\IntegrationTests;

use kristijorgji\DbToPhp\AppInfo;
use kristijorgji\DbToPhp\Console\DbToPhpApplication;
use kristijorgji\DbToPhp\FileSystem\FileSystem;
use kristijorgji\Tests\Helpers\CommandTestCaseHelper;
use kristijorgji\Tests\Helpers\TestCase;
use function chdir;
use function getcwd;
use function preg_replace;
use function sprintf;
use function unlink;
use const DIRECTORY_SEPARATOR;

final class InitCommandTest extends TestCase
{
    use CommandTestCaseHelper;

    private FileSystem $fileSystem;
    private string $originalCwd;
    private DbToPhpApplication $consoleApp;
    private string $command;

    protected function setUp(): void
    {
        $this->fileSystem = new FileSystem;
        $this->consoleApp = new DbToPhpApplication;
        $this->originalCwd = getcwd();
        $this->command = 'init';
    }

    protected function tearDown(): void
    {
        chdir($this->originalCwd);
    }

    public function testInit_without_path(): void
    {
        chdir(__DIR__);

        $this->runCommand(
            $this->consoleApp,
            sprintf('%s', $this->command),
        );

        $expectedConfigFilePath = __DIR__ . DIRECTORY_SEPARATOR . AppInfo::DEFAULT_CONFIG_FILENAME;
        $this->assertTrue(
            $this->fileSystem->exists($expectedConfigFilePath),
        );

        unlink($expectedConfigFilePath);
    }

    public function testInit_with_path(bool $deleteConfigAfter = true): void
    {
        chdir(__DIR__ . '/../');

        $expectedConfigFilePath = __DIR__ . DIRECTORY_SEPARATOR . AppInfo::DEFAULT_CONFIG_FILENAME;

        $this->runCommand(
            $this->consoleApp,
            sprintf('%s %s', $this->command, 'integration'),
        );

        $this->assertTrue(
            $this->fileSystem->exists($expectedConfigFilePath),
        );

        if ($deleteConfigAfter) {
            unlink($expectedConfigFilePath);
        }
    }

    public function testInit_already_exists(): void
    {
        chdir(__DIR__);

        $expectedConfigFilePath = __DIR__ . DIRECTORY_SEPARATOR . AppInfo::DEFAULT_CONFIG_FILENAME;
        $this->fileSystem->write($expectedConfigFilePath, self::randomString());

        $output = $this->runCommand(
            $this->consoleApp,
            sprintf('%s', $this->command),
        );

        $normalizedOutput = preg_replace('/\s+/', ' ', $output);
        $this->assertMatchesRegularExpression('#The file ".*" already exists#', $normalizedOutput);

        unlink($expectedConfigFilePath);
    }

    public function testInit_not_existing_directory(): void
    {
        chdir(__DIR__);

        $output = $this->runCommand(
            $this->consoleApp,
            sprintf('%s %s', $this->command, self::randomString() . DIRECTORY_SEPARATOR . self::randomString()),
        );

        $normalizedOutput = preg_replace('/\s+/', ' ', $output);
        $this->assertMatchesRegularExpression('#Cannot write ".+"\. Please check#', $normalizedOutput);
    }
}
