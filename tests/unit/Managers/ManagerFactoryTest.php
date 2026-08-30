<?php declare(strict_types = 1);

namespace kristijorgji\UnitTests\Managers;

use kristijorgji\DbToPhp\DatabaseDrivers;
use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterFactory;
use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterInterface;
use kristijorgji\DbToPhp\Managers\ManagerFactory;
use kristijorgji\DbToPhp\Managers\Php\PhpManager;
use kristijorgji\Tests\Helpers\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use function array_map;
use function array_values;
use function range;

class ManagerFactoryTest extends TestCase
{
    private DatabaseAdapterFactory&MockObject $databaseAdapterFactory;
    private ManagerFactory $managerFactory;

    protected function setUp(): void
    {
        $this->databaseAdapterFactory = $this->getMockBuilder(DatabaseAdapterFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->managerFactory = new ManagerFactory(
            $this->databaseAdapterFactory,
        );
    }

    /**     * @param array $config
     */
    #[DataProvider('getProvider')]
    public function testGet(array $config, string $expectedManagerClass): void
    {
        $this->databaseAdapterFactory->expects($this->once())
            ->method('get')
            ->with($config['databaseDriver'], $config['connection'])
            ->willReturn($this->getMockBuilder(DatabaseAdapterInterface::class)->getMock());

        $actualManager = $this->managerFactory->get($config);

        $this->assertInstanceOf($expectedManagerClass, $actualManager);
    }

    public static function getProvider(): array
    {
        $config = [
            'typeHint' => true,
            'databaseDriver' => DatabaseDrivers::MYSQL,
            'connection' => [],
            'entities' => ['namespace' => 'Entities'],
            'factories' => [],
        ];

        return [
            array_values(array_map(function () use ($config) {
                return [$config, PhpManager::class];
            }, range(0, 0)))[0],
        ];
    }
}
