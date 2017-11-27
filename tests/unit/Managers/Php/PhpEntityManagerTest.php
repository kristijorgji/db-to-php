<?php

namespace kristijorgji\UnitTests\Managers\Php;

use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterInterface;
use kristijorgji\DbToPhp\Db\Fields\Field;
use kristijorgji\DbToPhp\Db\Fields\FieldsCollection;
use kristijorgji\DbToPhp\Db\Table;
use kristijorgji\DbToPhp\Db\TablesCollection;
use kristijorgji\DbToPhp\FileSystem\FileSystemInterface;
use kristijorgji\DbToPhp\Managers\Php\PhpEntityManager;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapperInterface;
use kristijorgji\DbToPhp\Rules\Php\PhpAccessModifiers;
use kristijorgji\DbToPhp\Rules\Php\PhpPropertiesCollection;
use kristijorgji\DbToPhp\Rules\Php\PhpProperty;
use kristijorgji\Tests\Factories\Db\Fields\FieldFactory;
use kristijorgji\Tests\Factories\Db\Fields\FieldsCollectionFactory;
use kristijorgji\Tests\Factories\Rules\Php\PhpTypeFactory;
use kristijorgji\Tests\Helpers\TestCase;
use kristijorgji\UnitTests\Generators\Php\SamplePhpProperties;

class PhpEntityManagerTest extends AbstractPhpManagerTestCase
{
    use SamplePhpProperties;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var PhpEntityManager
     */
    protected $manager;

    public function setUp()
    {
        parent::setUp();
        $this->config = $this->config['entities'];
        $this->createManager();
    }

    private function createManager()
    {
        $this->manager = new PhpEntityManager(
            $this->databaseAdapter,
            $this->typeMapper,
            $this->fileSystem,
            $this->typeHint,
            $this->config
        );
    }

    public function testGenerateEntities()
    {
        $this->selfPartialMock(['filterTables', 'generateEntity']);

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
            ->method('filterTables')
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

        $returnedFields = FieldsCollectionFactory::make();

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
            ->with($this->config['outputDirectory'] . '/TestTableEntity.php', $this->anything());

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
                new PhpAccessModifiers($this->config['properties']['accessModifier']),
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
        $this->config['tableToEntityClassName']['some_specialTable'] = 'UseThisNameEntity';
        $this->createManager();
        $actual = $this->manager->formClassName('some_specialTable');
        $this->assertEquals('UseThisNameEntity', $actual);
    }

    private function selfPartialMock(array $methodsToMock)
    {
        $this->manager = $this->getMockBuilder(PhpEntityManager::class)
            ->setConstructorArgs([
                    $this->databaseAdapter,
                    $this->typeMapper,
                    $this->fileSystem,
                    $this->typeHint,
                    $this->config
                ]
            )
            ->setMethods($methodsToMock)
            ->getMock();
    }
}
