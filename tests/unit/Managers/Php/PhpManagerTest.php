<?php

namespace kristijorgji\UnitTests\Managers\Php;

use kristijorgji\DbToPhp\DatabaseDrivers;
use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterInterface;
use kristijorgji\DbToPhp\Db\Fields\Field;
use kristijorgji\DbToPhp\Db\Fields\FieldsCollection;
use kristijorgji\DbToPhp\Db\Table;
use kristijorgji\DbToPhp\Db\TablesCollection;
use kristijorgji\DbToPhp\FileSystem\FileSystemInterface;
use kristijorgji\DbToPhp\Languages;
use kristijorgji\DbToPhp\Managers\Php\PhpManager;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapperInterface;
use kristijorgji\DbToPhp\Rules\Php\PhpAccessModifiers;
use kristijorgji\DbToPhp\Rules\Php\PhpPropertiesCollection;
use kristijorgji\DbToPhp\Rules\Php\PhpProperty;
use kristijorgji\Tests\Factories\Db\Fields\FieldFactory;
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
        $this->markTestIncomplete();
    }

    public function testGenerateEntity()
    {
        $this->markTestIncomplete();
    }

    public function testGenerateFactories()
    {
        $this->markTestIncomplete();
    }

    public function testGenerateFactory()
    {
        $this->markTestIncomplete();
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
