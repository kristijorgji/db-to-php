<?php

namespace kristijorgji\UnitTests\Managers\Php;

use kristijorgji\DbToPhp\Db\Table;
use kristijorgji\DbToPhp\Db\TablesCollection;
use kristijorgji\DbToPhp\Managers\Php\PhpEntityFactoryManager;
use kristijorgji\DbToPhp\Managers\Php\PhpEntityManager;
use kristijorgji\Tests\Factories\Db\Fields\FieldsCollectionFactory;
use kristijorgji\Tests\Factories\Db\TablesCollectionFactory;

class PhpEntityFactoryManagerTest extends AbstractPhpManagerTestCase
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $entityManager;

    /**
     * @var PhpEntityFactoryManager
     */
    protected $manager;

    public function setUp()
    {
        parent::setUp();
        $this->config = $this->config['factories'];
        $this->createManager();
    }

    private function createManager()
    {
        $this->entityManager = $this->getMockBuilder(PhpEntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->manager = new PhpEntityFactoryManager(
            $this->databaseAdapter,
            $this->typeMapper,
            $this->fileSystem,
            $this->typeHint,
            $this->config,
            $this->entityManager
        );
    }

    public function testGenerateFactories()
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

        $this->manager->expects($this->exactly(count($returnedTables->all())))
            ->method('generateFactory')
            ->withConsecutive(... array_map(function ($table) {
                return [$table->getName()];
            }, $returnedTables->all()));

        $this->manager->generateFactories();
    }

    /**
     * @dataProvider formClassNameProvider
     * @param string $tableName
     * @param string $entityClassName
     * @param string $expected
     */
    public function testFormClassName(string $tableName, string $entityClassName, string $expected)
    {
        $actual = $this->manager->formClassName($tableName, $entityClassName);
        $this->assertEquals($expected, $actual);
    }

    public function formClassNameProvider()
    {
        return [
            ['super', 'SuperEntity', 'SuperEntityFactory'],
            ['Real_table', 'Real_TableEntity', 'Real_TableEntityFactory'],
        ];
    }

    public function testFormClassName_use_config()
    {
        $this->config['tableToEntityFactoryClassName']['some_specialTable'] = 'UseThisNameEntityFactory';
        $this->createManager();
        $actual = $this->manager->formClassName('some_specialTable', self::randomString());
        $this->assertEquals('UseThisNameEntityFactory', $actual);
    }

    private function selfPartialMock(array $methodsToMock)
    {
        $this->manager = $this->getMockBuilder(PhpEntityFactoryManager::class)
            ->setConstructorArgs([
                    $this->databaseAdapter,
                    $this->typeMapper,
                    $this->fileSystem,
                    $this->typeHint,
                    $this->config,
                    $this->entityManager
                ]
            )
            ->setMethods($methodsToMock)
            ->getMock();
    }
}
