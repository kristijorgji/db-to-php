<?php

namespace kristijorgji\UnitTests\Managers\Php;

use kristijorgji\DbToPhp\Data\AbstractEntity;
use kristijorgji\DbToPhp\Db\Fields\FieldsCollection;
use kristijorgji\DbToPhp\Db\Table;
use kristijorgji\DbToPhp\Db\TablesCollection;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpClassGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpEntityGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpGetterGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpPropertyGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpSetterGeneratorConfig;
use kristijorgji\DbToPhp\Managers\Exceptions\GenerateException;
use kristijorgji\DbToPhp\Managers\GenerateResponse;
use kristijorgji\DbToPhp\Managers\Php\PhpEntityManager;
use kristijorgji\DbToPhp\Rules\Php\PhpAccessModifiers;
use kristijorgji\DbToPhp\Rules\Php\PhpPropertiesCollection;
use kristijorgji\DbToPhp\Rules\Php\PhpProperty;
use kristijorgji\DbToPhp\Support\StringCollection;
use kristijorgji\Tests\Factories\Db\Fields\FieldFactory;
use kristijorgji\Tests\Factories\Db\Fields\FieldsCollectionFactory;
use kristijorgji\Tests\Factories\Db\TablesCollectionFactory;
use kristijorgji\Tests\Factories\Rules\Php\PhpTypeFactory;
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

    public function setUp(): void
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

    public function testGenerateEntities_on_error()
    {
        $this->selfPartialMock(['filterTables', 'generateEntity']);

        $returnedTables = TablesCollectionFactory::make();

        $this->databaseAdapter->expects($this->once())
            ->method('getTables')
            ->willReturn($returnedTables);

        $this->manager->expects($this->once())
            ->method('filterTables')
            ->with($returnedTables)
            ->willReturn($returnedTables);


        $partialResponse = new GenerateResponse();
        $partialResponse->addPath('test');

        $this->manager->expects($this->once())
            ->method('generateEntity')
            ->willThrowException(new \Exception());

        try {
            $this->manager->generateEntities();
        } catch (\Exception $e) {
            $this->assertInstanceOf(GenerateException::class, $e);
        }
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
            ->with(
                $this->config['outputDirectory'] . '/TestTableEntity.php',
                $this->anything()
            );

        $this->manager->generateEntity($tableName);
    }

    /**
     * @dataProvider parseConfigForEntityProvider
     * @param array $config
     * @param string $tableName
     * @param PhpEntityGeneratorConfig $expected
     */
    public function testParseConfigForEntity(
        array $config,
        string $tableName,
        PhpEntityGeneratorConfig $expected
    ) {

        $this->config = $config;
        $this->createManager();

        $method = $this->getPrivateMethod($this->manager, 'parseConfigForEntity');
        $actual = $method->invokeArgs(
            $this->manager,
            [
                $tableName
            ]
        );

        $this->assertEquals($expected, $actual);
    }

    public function parseConfigForEntityProvider()
    {
        return [
            'should_not_track_changes' => [
                [
                    'includeTables' => ['*'],
                    'tableToEntityClassName' => [
                        'test' => 'SuperEntity'
                    ],
                    'outputDirectory' => 'Entities',
                    'namespace' => 'Entities',
                    'includeAnnotations' => true,
                    'includeSetters' => true,
                    'includeGetters' => true,
                    'fluentSetters' => true,
                    'properties' => [
                        'accessModifier' => \kristijorgji\DbToPhp\Rules\Php\PhpAccessModifiers::PRIVATE
                    ],
                    'trackChangesFor' => []
                ],
                'test',
                new PhpEntityGeneratorConfig(
                    new PhpClassGeneratorConfig(
                        'Entities',
                        'SuperEntity',
                        new StringCollection(... []),
                        null,
                        true
                    ),
                    true,
                    true,
                    new PhpSetterGeneratorConfig(
                        true,
                        true,
                        true
                    ),
                    new PhpGetterGeneratorConfig(
                        true,
                        true
                    ),
                    new PhpPropertyGeneratorConfig(
                        true,
                        false
                    ),
                    false
                )
            ],
            'should_track_changes' => [
                [
                    'includeTables' => ['*'],
                    'tableToEntityClassName' => [
                        'test' => 'SuperEntity'
                    ],
                    'outputDirectory' => 'Entities',
                    'namespace' => 'Entities',
                    'includeAnnotations' => true,
                    'includeSetters' => true,
                    'includeGetters' => true,
                    'fluentSetters' => true,
                    'properties' => [
                        'accessModifier' => \kristijorgji\DbToPhp\Rules\Php\PhpAccessModifiers::PRIVATE
                    ],
                    'trackChangesFor' => ['test']
                ],
                'test',
                new PhpEntityGeneratorConfig(
                    new PhpClassGeneratorConfig(
                        'Entities',
                        'SuperEntity',
                        new StringCollection(... [AbstractEntity::class]),
                        'AbstractEntity',
                        true
                    ),
                    true,
                    true,
                    new PhpSetterGeneratorConfig(
                        true,
                        true,
                        true
                    ),
                    new PhpGetterGeneratorConfig(
                        true,
                        true
                    ),
                    new PhpPropertyGeneratorConfig(
                        true,
                        false
                    ),
                    true
                )
            ]
        ];
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
