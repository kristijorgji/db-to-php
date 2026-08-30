<?php declare(strict_types = 1);

namespace kristijorgji\UnitTests\Config;

use kristijorgji\DbToPhp\Config\ConfigFactory;
use kristijorgji\DbToPhp\Config\Exceptions\ConfigParserException;
use kristijorgji\DbToPhp\FileSystem\FileSystemInterface;
use kristijorgji\Tests\Helpers\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

final class ConfigFactoryTest extends TestCase
{
    /**
     * @var MockObject&FileSystemInterface
     */
    private MockObject $fileSystem;

    private ConfigFactory $configFactory;

    protected function setUp(): void
    {
        $this->fileSystem = $this->createMock(FileSystemInterface::class);
        $this->configFactory = new ConfigFactory(
            $this->fileSystem,
        );
    }

    public function testGet_php(): void
    {
        $path = __DIR__ . '/dummyConfig.php';
        $this->fileSystem->expects($this->once())
            ->method('getFileExtension')
            ->willReturn('php');

        $config = $this->configFactory->get($path);
        $expected = [
            'success' => true,
        ];

        $this->assertEquals($expected, $config);
    }

    public function testGet_unknown(): void
    {
        $this->fileSystem->expects($this->once())
            ->method('getFileExtension')
            ->willReturn(self::randomString());

        $this->expectException(ConfigParserException::class);
        $this->configFactory->get(self::randomString());
    }
}
