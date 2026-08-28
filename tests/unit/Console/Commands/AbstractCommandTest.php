<?php declare(strict_types = 1);

namespace kristijorgji\UnitTests\Console\Commands;

use kristijorgji\DbToPhp\AppInfo;
use kristijorgji\DbToPhp\Config\ConfigFactory;
use kristijorgji\DbToPhp\Console\Commands\AbstractCommand;
use kristijorgji\Tests\Helpers\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use function getcwd;
use function sprintf;
use const DIRECTORY_SEPARATOR;

class AbstractCommandTest extends TestCase
{
    private AbstractCommand $command;

    /**
     * @var PHPUnit\Framework\MockObject\MockObject
     */
    private MockObject $configFactory;

    private string $appName;

    protected function setUp(): void
    {
        $this->configFactory = $this->getMockBuilder(ConfigFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->appName = sprintf('%s Application', AppInfo::NAME);

        $this->command = $this->getMockBuilder(AbstractCommand::class)
            ->setConstructorArgs([
                $this->configFactory,
                self::randomString(),
            ])
            ->onlyMethods([])
            ->getMock();
    }

    public function testLocateDefaultConfigFile(): void
    {
        $method = $this->getPrivateMethod($this->command, 'locateDefaultConfigFile');
        $located = $method->invoke($this->command);
        $expected = getcwd() . DIRECTORY_SEPARATOR . AppInfo::DEFAULT_CONFIG_FILENAME;

        $this->assertEquals($expected, $located);
    }
}
