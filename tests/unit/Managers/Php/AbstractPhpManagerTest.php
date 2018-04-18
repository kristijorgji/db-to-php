<?php

namespace kristijorgji\UnitTests\Managers\Php;

use kristijorgji\DbToPhp\Db\TablesCollection;
use kristijorgji\DbToPhp\Managers\Exceptions\TableDoesNotExistException;
use kristijorgji\DbToPhp\Managers\Php\AbstractPhpManager;
use kristijorgji\Tests\Factories\Db\TablesCollectionFactory;

class AbstractPhpManagerTest extends AbstractPhpManagerTestCase
{
    /**
     * @var AbstractPhpManager
     */
    protected $manager;

    public function setUp()
    {
        parent::setUp();
        $this->createManager();
    }

    public function testFilterTables()
    {
        $tables = TablesCollectionFactory::make();
        $filteredTables = $this->manager->filterTables(
            $tables,
            $this->config['entities']['includeTables']
        );

        $this->assertEquals($tables, $filteredTables);
    }

    public function testFilterTables_only_some()
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

        $actualFilteredTables = $this->manager->filterTables(
            $tables,
            $this->config['entities']['includeTables']
        );

        $expectedTables = new TablesCollection(... $expectedTables);

        $this->assertEquals($expectedTables, $actualFilteredTables);
    }

    public function testFilterTables_non_existing()
    {
        $nrTotalTables = 2;
        $tables = TablesCollectionFactory::make($nrTotalTables);

        $nonExistingTable = self::randomString() . microtime(true);
        $this->config['entities']['includeTables'] = [
            $tables->getAt(0)->getName(),
            $nonExistingTable
        ];

        $this->createManager();

        $thrownException = null;
        try {
            $this->manager->filterTables(
                $tables,
                $this->config['entities']['includeTables']
            );
        } catch (\Exception $e) {
            $thrownException = $e;
        }

        $this->assertTrue($thrownException instanceof TableDoesNotExistException);
        $this->assertEquals($nonExistingTable, $thrownException->getTableName());
    }

    private function createManager()
    {
        $this->manager = $this->getMockBuilder(AbstractPhpManager::class)
            ->setConstructorArgs([
                $this->databaseAdapter,
                $this->typeMapper,
                $this->fileSystem,
                $this->typeHint
            ])
            ->getMockForAbstractClass();
    }
}
