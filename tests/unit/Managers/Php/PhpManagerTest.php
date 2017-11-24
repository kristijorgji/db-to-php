<?php

namespace kristijorgji\UnitTests\Managers\Php;

use kristijorgji\DbToPhp\DatabaseDrivers;
use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterInterface;
use kristijorgji\DbToPhp\Db\Field;
use kristijorgji\DbToPhp\Db\FieldsCollection;
use kristijorgji\DbToPhp\Db\Table;
use kristijorgji\DbToPhp\Db\TablesCollection;
use kristijorgji\DbToPhp\FileSystem\FileSystemInterface;
use kristijorgji\DbToPhp\Languages;
use kristijorgji\DbToPhp\Managers\Php\PhpManager;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapperInterface;
use kristijorgji\DbToPhp\Rules\Php\PhpAccessModifiers;
use kristijorgji\DbToPhp\Rules\Php\PhpPropertiesCollection;
use kristijorgji\DbToPhp\Rules\Php\PhpProperty;
use kristijorgji\Tests\Factories\Db\FieldFactory;
use kristijorgji\Tests\Factories\Db\TablesCollectionFactory;
use kristijorgji\Tests\Factories\Rules\Php\PhpTypeFactory;
use kristijorgji\Tests\Helpers\TestCase;
use kristijorgji\UnitTests\Generators\Php\SamplePhpProperties;

class PhpManagerTest extends TestCase
{
    use SamplePhpProperties;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var PhpManager
     */
    protected $manager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $databaseAdapter;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $typeMapper;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $fileSystem;

    public function setUp()
    {
        $this->config = require $this->baseTestsPath('integration/MySql/Php/config.php');

        $this->databaseAdapter = $this->getMockBuilder(DatabaseAdapterInterface::class)->getMock();
        $this->typeMapper = $this->getMockBuilder(PhpTypeMapperInterface::class)->getMock();
        $this->fileSystem = $this->getMockBuilder(FileSystemInterface::class)->getMock();

        $this->createManager();
    }

    public function testGenerateEntities()
    {
        $this->selfPartialMock(['filterEntityTables', 'generateEntity']);

        $returnedTables = new TablesCollection(...
            [
                new Table('users'),
                new Table('items'),
                new Table('orders')
            ]
        );

        $this->databaseAdapter->expects($this->once())
            ->method('getTables')
            ->willReturn($returnedTables);

        $this->manager->expects($this->once())
            ->method('filterEntityTables')
            ->with($returnedTables)
            ->willReturn($returnedTables);

        $this->manager->expects($this->exactly(count($returnedTables->all())))
            ->method('generateEntity')
            ->withConsecutive(... array_map(function ($table) {
                return [$table->getName()];
            }, $returnedTables->all()));

        $this->manager->generateEntities();
    }

    public function testGenerateEntity()
    {
        $this->selfPartialMock(['formProperties']);
        $tableName = 'test_table';

        $returnedFields = new FieldsCollection(... [
                new Field('df', 'int(11)', true) ,
                new Field('dadfa', 'varchar(50)', true) ,
            ]
        );

        $this->databaseAdapter->expects($this->once())
            ->method('getFields')
            ->with($tableName)
            ->willReturn($returnedFields);

        $returnedProperties = $this->getSampleProperties();

        $this->manager->expects($this->once())
            ->method('formProperties')
            ->with($returnedFields)
            ->willReturn($returnedProperties);

        $this->fileSystem->expects($this->once())
            ->method('write')
            ->with($this->config['entities']['outputDirectory'] . '/TestTableEntity.php', $this->anything());

        $this->manager->generateEntity($tableName);
    }

    public function testFormProperties()
    {
        $fields = new FieldsCollection(... array_map(function () {
            return FieldFactory::make();
        }, range(0, 4)));

        $returnedTypes = array_map(function () {
            return PhpTypeFactory::make();
            }, $fields->all());

        $this->typeMapper->expects($this->exactly(count($fields->all())))
            ->method('map')
            ->withConsecutive(... array_map(function ($field) {
                return [$field];
            }, $fields->all()))
            ->willReturnOnConsecutiveCalls(... $returnedTypes);

        $expectedProperties = new PhpPropertiesCollection(... array_map(function ($field, $type) {
            return new PhpProperty(
                new PhpAccessModifiers($this->config['entities']['properties']['accessModifier']),
                $type,
                snakeToCamelCase($field->getName())
            );
        }, $fields->all(), $returnedTypes));


        $actualProperties = $this->manager->formProperties($fields);

        $this->assertEquals($expectedProperties, $actualProperties);
    }

    /**
     * @dataProvider formClassNameProvider
     * @param string $tableName
     * @param string $expected
     */
    public function testFormClassName(string $tableName, string $expected)
    {
        $actual = $this->manager->formClassName($tableName);
        $this->assertEquals($expected, $actual);
    }

    public function formClassNameProvider()
    {
        return [
            ['super_table', 'SuperTableEntity'],
            ['Real_table', 'RealTableEntity'],
            ['anotherTable', 'AnotherTableEntity'],
            ['evenmoretables', 'EvenmoretablesEntity'],
            ['some-different-table', 'Some-different-tableEntity'],
            ['table_entity', 'TableEntityEntity'],
        ];
    }

    public function testFormClassName_use_config()
    {
        $this->config['entities']['tableToEntityClassName']['some_specialTable'] = 'UseThisNameEntity';
        $this->createManager();
        $actual = $this->manager->formClassName('some_specialTable');
        $this->assertEquals('UseThisNameEntity', $actual);
    }

    public function testfilterEntityTables()
    {
        $tables = TablesCollectionFactory::make();
        $filterEntityTablesMethod = $this->getPrivateMethod($this->manager, 'filterEntityTables');
        $filteredTables = $filterEntityTablesMethod->invokeArgs($this->manager, [$tables]);

        $this->assertEquals($tables, $filteredTables);
    }

    public function testfilterEntityTables_only_some()
    {
        $nrTotalTables = 10;
        $tables = TablesCollectionFactory::make($nrTotalTables);

        $this->config['entities']['includeTables'] = [];
        $randomChosenTablesNr = 5;
        $randomChosenIndexes = [];
        $expectedTables = [];
        for ($i = 0; $i < $randomChosenTablesNr; $i++) {
            do {
                $randomChosenIndex = rand(0, $nrTotalTables - 1);
            } while (in_array($randomChosenIndex, $randomChosenIndexes));

            $randomChosenIndexes[] = $randomChosenIndex;
        }

        sort($randomChosenIndexes, SORT_ASC);

        foreach ($randomChosenIndexes as $randomChosenIndex) {
            $this->config['entities']['includeTables'][] = $tables->getAt($randomChosenIndex)->getName();
            $expectedTables[] = $tables->getAt($randomChosenIndex);
        }

        $this->createManager();

        $filterEntityTablesMethod = $this->getPrivateMethod($this->manager, 'filterEntityTables');
        $filteredTables = $filterEntityTablesMethod->invokeArgs($this->manager, [$tables]);

        $expectedTables = new TablesCollection(... $expectedTables);

        $this->assertEquals($expectedTables, $filteredTables);
    }

    private function createManager()
    {
        $this->manager = new PhpManager(
            $this->config,
            $this->databaseAdapter,
            $this->typeMapper,
            $this->fileSystem
        );
    }

    private function selfPartialMock(array $methodsToMock)
    {
        $this->manager = $this->getMockBuilder(PhpManager::class)
            ->setConstructorArgs([
                    $this->config,
                    $this->databaseAdapter,
                    $this->typeMapper,
                    $this->fileSystem
                ]
            )
            ->setMethods($methodsToMock)
            ->getMock();
    }
}
