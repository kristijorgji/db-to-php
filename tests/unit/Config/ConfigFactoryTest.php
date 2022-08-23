<?php

namespace kristijorgji\UnitTests\Config;

use kristijorgji\DbToPhp\Config\ConfigFactory;
use kristijorgji\DbToPhp\Config\Exceptions\ConfigParserException;
use kristijorgji\DbToPhp\FileSystem\FileSystemInterface;
use kristijorgji\Tests\Helpers\TestCase;

class ConfigFactoryTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $fileSystem;

    /**
     * @var ConfigFactory
     */
    private $configFactory;

    public function setUp(): void
    {
        $this->fileSystem = $this->getMockBuilder(FileSystemInterface::class)->getMock();
        $this->configFactory = new ConfigFactory(
            $this->fileSystem
        );
    }

    public function testGet_php()
    {
        $path = __DIR__ . '/dummyConfig.php';
        $this->fileSystem->expects($this->once())
            ->method('getFileExtension')
            ->willReturn('php');

        $config = $this->configFactory->get($path);
        $expected = [
            'success' => true
        ];

        $this->assertEquals($expected, $config);
    }

    public function testGet_unknown()
    {
        $this->fileSystem->expects($this->once())
            ->method('getFileExtension')
            ->willReturn(self::randomString());

        $this->expectException(ConfigParserException::class);
        $this->configFactory->get(self::randomString());
    }
}
