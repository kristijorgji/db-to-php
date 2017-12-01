<?php

namespace kristijorgji\UnitTests\Console\Commands;

use kristijorgji\DbToPhp\AppInfo;
use kristijorgji\DbToPhp\Config\ConfigFactory;
use kristijorgji\DbToPhp\Console\Commands\AbstractCommand;
use kristijorgji\Tests\Helpers\TestCase;

class AbstractCommandTest extends TestCase
{
    /**
     * @var AbstractCommand
     */
    private $command;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $configFactory;

    /**
     * @var string
     */
    private $appName;

    public function setUp()
    {
        $this->configFactory = $this->getMockBuilder(ConfigFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->appName = sprintf('%s Application', AppInfo::NAME);

        $this->command = $this->getMockBuilder(AbstractCommand::class)
            ->setConstructorArgs([
                $this->configFactory,
                self::randomString()
            ])
            ->getMockForAbstractClass();
    }

    public function testLocateDefaultConfigFile()
    {
        $method = $this->getPrivateMethod($this->command, 'locateDefaultConfigFile');
        $located = $method->invoke($this->command);
        $expected = getcwd() . DIRECTORY_SEPARATOR . AppInfo::DEFAULT_CONFIG_FILENAME;

        $this->assertEquals($expected, $located);
    }
}
