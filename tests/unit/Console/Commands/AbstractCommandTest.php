<?php declare(strict_types = 1);

namespace kristijorgji\UnitTests\Console\Commands;

use kristijorgji\DbToPhp\AppInfo;
use kristijorgji\DbToPhp\Config\ConfigFactory;
use kristijorgji\DbToPhp\Console\Commands\AbstractCommand;
use kristijorgji\Tests\Helpers\TestCase;
use function getcwd;
use const DIRECTORY_SEPARATOR;

final class AbstractCommandTest extends TestCase
{
    private AbstractCommand $command;

    protected function setUp(): void
    {
        $configFactory = $this->createStub(ConfigFactory::class);
        $this->command = new class ($configFactory, self::randomString()) extends AbstractCommand {
        };
    }

    public function testLocateDefaultConfigFile(): void
    {
        $method = $this->getPrivateMethod($this->command, 'locateDefaultConfigFile');
        $located = $method->invoke($this->command);
        $expected = getcwd() . DIRECTORY_SEPARATOR . AppInfo::DEFAULT_CONFIG_FILENAME;

        $this->assertEquals($expected, $located);
    }
}
