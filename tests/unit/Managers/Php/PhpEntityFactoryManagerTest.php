<?php declare(strict_types = 1);

namespace kristijorgji\UnitTests\Managers\Php;

use Exception;
use kristijorgji\DbToPhp\Managers\Exceptions\GenerateException;
use kristijorgji\DbToPhp\Managers\GenerateResponse;
use kristijorgji\DbToPhp\Managers\Php\PhpEntityFactoryManager;
use kristijorgji\DbToPhp\Managers\Php\PhpEntityManager;
use kristijorgji\Tests\Factories\Db\TablesCollectionFactory;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use Throwable;
use function array_map;
use function count;

final class PhpEntityFactoryManagerTest extends AbstractPhpManagerTestCase
{
    protected array $config;
    private PhpEntityManager&MockObject $entityManager;
    protected PhpEntityFactoryManager&MockObject $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = $this->config['factories'];
        $this->createManager();
    }

    private function createManager(): void
    {
        $this->entityManager = $this->createMock(PhpEntityManager::class);

        $this->manager = $this->getMockBuilder(PhpEntityFactoryManager::class)
            ->setConstructorArgs([
                $this->databaseAdapter,
                $this->typeMapper,
                $this->fileSystem,
                $this->typeHint,
                $this->config,
                $this->entityManager,
            ])
            ->onlyMethods([])
            ->getMock();
    }

    public function testGenerateFactories(): void
    {
        $this->selfPartialMock(['filterTables', 'generateFactory']);

        $returnedTables = TablesCollectionFactory::make();

        $this->databaseAdapter->expects($this->once())
            ->method('getTables')
            ->willReturn($returnedTables);

        $this->manager->expects($this->once())
            ->method('filterTables')
            ->with($returnedTables)
            ->willReturn($returnedTables);

        $expectedTableNames = array_map(fn($table) => $table->getName(), $returnedTables->all());

        $actualTableNames = [];
        $this->manager->expects($this->exactly(count($returnedTables->all())))
            ->method('generateFactory')
            ->willReturnCallback(function (string $tableName) use (&$actualTableNames) {
                $actualTableNames[] = $tableName;
                return '';
            });

        $this->manager->generateFactories();

        $this->assertSame($expectedTableNames, $actualTableNames);
    }

    public function testGenerateFactories_on_error(): void
    {
        $this->selfPartialMock(['filterTables', 'generateFactory']);

        $returnedTables = TablesCollectionFactory::make();

        $this->databaseAdapter->expects($this->once())
            ->method('getTables')
            ->willReturn($returnedTables);

        $this->manager->expects($this->once())
            ->method('filterTables')
            ->with($returnedTables)
            ->willReturn($returnedTables);

        $partialResponse = new GenerateResponse;
        $partialResponse->addPath('test');

        $this->manager->expects($this->once())
            ->method('generateFactory')
            ->willThrowException(new Exception);

        try {
            $this->manager->generateFactories();
        } catch (Throwable $e) {
            $this->assertInstanceOf(GenerateException::class, $e);
        }
    }

    /**     * @param string $tableName
     */
    #[DataProvider('formClassNameProvider')]
    public function testFormClassName(string $tableName, string $entityClassName, string $expected): void
    {
        $actual = $this->manager->formClassName($tableName, $entityClassName);
        $this->assertSame($expected, $actual);
    }

    public static function formClassNameProvider(): array
    {
        return [
            ['super', 'SuperEntity', 'SuperEntityFactory'],
            ['Real_table', 'Real_TableEntity', 'Real_TableEntityFactory'],
        ];
    }

    public function testFormClassName_use_config(): void
    {
        $this->config['tableToEntityFactoryClassName']['some_specialTable'] = 'UseThisNameEntityFactory';
        $this->createManager();
        $actual = $this->manager->formClassName('some_specialTable', self::randomString());
        $this->assertSame('UseThisNameEntityFactory', $actual);
    }

    private function selfPartialMock(array $methodsToMock): void
    {
        $this->manager = $this->getMockBuilder(PhpEntityFactoryManager::class)
            ->setConstructorArgs([
                    $this->databaseAdapter,
                    $this->typeMapper,
                    $this->fileSystem,
                    $this->typeHint,
                    $this->config,
                    $this->entityManager,
                ])
            ->onlyMethods($methodsToMock)
            ->getMock();
    }
}
