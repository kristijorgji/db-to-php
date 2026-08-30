<?php declare(strict_types = 1);

namespace kristijorgji\UnitTests\Managers\Php;

use Exception;
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
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use Throwable;
use function array_map;
use function count;
use function range;
use function snakeToCamelCase;

final class PhpEntityManagerTest extends AbstractPhpManagerTestCase
{
    use SamplePhpProperties;

    protected array $config;
    protected PhpEntityManager&MockObject $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = $this->config['entities'];
        $this->createManager();
    }

    private function createManager(): void
    {
        $this->selfPartialMock([]);
    }

    public function testGenerateEntities(): void
    {
        $this->selfPartialMock(['filterTables', 'generateEntity']);

        $returnedTables = new TablesCollection(...
            [
                new Table('users'),
                new Table('items'),
                new Table('orders'),
            ]);

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
            ->method('generateEntity')
            ->willReturnCallback(function (string $tableName) use (&$actualTableNames) {
                $actualTableNames[] = $tableName;
                return '';
            });

        $this->manager->generateEntities();

        $this->assertSame($expectedTableNames, $actualTableNames);
    }

    public function testGenerateEntities_on_error(): void
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

        $partialResponse = new GenerateResponse;
        $partialResponse->addPath('test');

        $this->manager->expects($this->once())
            ->method('generateEntity')
            ->willThrowException(new Exception);

        try {
            $this->manager->generateEntities();
        } catch (Throwable $e) {
            $this->assertInstanceOf(GenerateException::class, $e);
        }
    }

    public function testGenerateEntity(): void
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
                $this->anything(),
            );

        $this->manager->generateEntity($tableName);
    }

    /**     * @param array $config
     */
    #[DataProvider('parseConfigForEntityProvider')]
    public function testParseConfigForEntity(
        array $config,
        string $tableName,
        PhpEntityGeneratorConfig $expected,
    ): void {

        $this->config = $config;
        $this->createManager();

        $method = $this->getPrivateMethod($this->manager, 'parseConfigForEntity');
        $actual = $method->invokeArgs(
            $this->manager,
            [
                $tableName,
            ],
        );

        $this->assertEquals($expected, $actual);
    }

    public static function parseConfigForEntityProvider(): array
    {
        return [
            'should_not_track_changes' => [
                [
                    'includeTables' => ['*'],
                    'tableToEntityClassName' => [
                        'test' => 'SuperEntity',
                    ],
                    'outputDirectory' => 'Entities',
                    'namespace' => 'Entities',
                    'includeAnnotations' => true,
                    'includeSetters' => true,
                    'includeGetters' => true,
                    'fluentSetters' => true,
                    'properties' => [
                        'accessModifier' => PhpAccessModifiers::PRIVATE,
                    ],
                    'trackChangesFor' => [],
                ],
                'test',
                new PhpEntityGeneratorConfig(
                    new PhpClassGeneratorConfig(
                        'Entities',
                        'SuperEntity',
                        new StringCollection(... []),
                    ),
                    true,
                    true,
                    new PhpSetterGeneratorConfig(
                        true,
                        true,
                        true,
                    ),
                    new PhpGetterGeneratorConfig(
                        true,
                        true,
                    ),
                    new PhpPropertyGeneratorConfig(
                        true,
                        false,
                    ),
                    false,
                ),
            ],
            'should_track_changes' => [
                [
                    'includeTables' => ['*'],
                    'tableToEntityClassName' => [
                        'test' => 'SuperEntity',
                    ],
                    'outputDirectory' => 'Entities',
                    'namespace' => 'Entities',
                    'includeAnnotations' => true,
                    'includeSetters' => true,
                    'includeGetters' => true,
                    'fluentSetters' => true,
                    'properties' => [
                        'accessModifier' => PhpAccessModifiers::PRIVATE,
                    ],
                    'trackChangesFor' => ['test'],
                ],
                'test',
                new PhpEntityGeneratorConfig(
                    new PhpClassGeneratorConfig(
                        'Entities',
                        'SuperEntity',
                        new StringCollection(... [AbstractEntity::class]),
                        'AbstractEntity',
                    ),
                    true,
                    true,
                    new PhpSetterGeneratorConfig(
                        true,
                        true,
                        true,
                    ),
                    new PhpGetterGeneratorConfig(
                        true,
                        true,
                    ),
                    new PhpPropertyGeneratorConfig(
                        true,
                        false,
                    ),
                    true,
                ),
            ],
        ];
    }

    public function testFormProperties(): void
    {
        $fields = new FieldsCollection(... array_map(fn() => FieldFactory::make(), range(0, 4)));

        $returnedTypes = array_map(fn() => PhpTypeFactory::make(), $fields->all());

        $expectedFields = $fields->all();
        $actualFields = [];
        $mapCallIndex = 0;
        $this->typeMapper->expects($this->exactly(count($fields->all())))
            ->method('map')
            ->willReturnCallback(function ($field) use (&$actualFields, &$mapCallIndex, $returnedTypes) {
                $actualFields[] = $field;
                return $returnedTypes[$mapCallIndex++];
            });

        $expectedProperties = new PhpPropertiesCollection(... array_map(fn($field, $type) => new PhpProperty(
            $this->config['properties']['accessModifier'],
            $type,
            snakeToCamelCase($field->getName()),
        ), $fields->all(), $returnedTypes));

        $actualProperties = $this->manager->formProperties($fields);

        $this->assertSame($expectedFields, $actualFields);
        $this->assertEquals($expectedProperties, $actualProperties);
    }

    /**     * @param string $tableName
     */
    #[DataProvider('formClassNameProvider')]
    public function testFormClassName(string $tableName, string $expected): void
    {
        $actual = $this->manager->formClassName($tableName);
        $this->assertSame($expected, $actual);
    }

    public static function formClassNameProvider(): array
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

    public function testFormClassName_use_config(): void
    {
        $this->config['tableToEntityClassName']['some_specialTable'] = 'UseThisNameEntity';
        $this->createManager();
        $actual = $this->manager->formClassName('some_specialTable');
        $this->assertSame('UseThisNameEntity', $actual);
    }

    private function selfPartialMock(array $methodsToMock): void
    {
        $this->manager = $this->getMockBuilder(PhpEntityManager::class)
            ->setConstructorArgs([
                    $this->databaseAdapter,
                    $this->typeMapper,
                    $this->fileSystem,
                    $this->typeHint,
                    $this->config,
                ])
            ->onlyMethods($methodsToMock)
            ->getMock();
    }
}
