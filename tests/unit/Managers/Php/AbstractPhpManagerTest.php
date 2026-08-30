<?php declare(strict_types = 1);

namespace kristijorgji\UnitTests\Managers\Php;

use kristijorgji\DbToPhp\Db\TablesCollection;
use kristijorgji\DbToPhp\Managers\Exceptions\TableDoesNotExistException;
use kristijorgji\DbToPhp\Managers\Php\AbstractPhpManager;
use kristijorgji\Tests\Factories\Db\TablesCollectionFactory;
use Throwable;
use function in_array;
use function microtime;
use function random_int;
use function sort;

final class AbstractPhpManagerTest extends AbstractPhpManagerTestCase
{
    protected AbstractPhpManager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createManager();
    }

    public function testFilterTables(): void
    {
        $tables = TablesCollectionFactory::make();
        $filteredTables = $this->manager->filterTables(
            $tables,
            $this->config['entities']['includeTables'],
        );

        $this->assertEquals($tables, $filteredTables);
    }

    public function testFilterTables_only_some(): void
    {
        $nrTotalTables = 10;
        $tables = TablesCollectionFactory::make($nrTotalTables);

        $this->config['entities']['includeTables'] = [];
        $randomChosenTablesNr = 5;
        $randomChosenIndexes = [];
        $expectedTables = [];
        for ($i = 0; $i < $randomChosenTablesNr; $i++) {
            do {
                $randomChosenIndex = random_int(0, $nrTotalTables - 1);
            } while (in_array($randomChosenIndex, $randomChosenIndexes));

            $randomChosenIndexes[] = $randomChosenIndex;
        }

        sort($randomChosenIndexes);

        foreach ($randomChosenIndexes as $randomChosenIndex) {
            $this->config['entities']['includeTables'][] = $tables->getAt($randomChosenIndex)->getName();
            $expectedTables[] = $tables->getAt($randomChosenIndex);
        }

        $this->createManager();

        $actualFilteredTables = $this->manager->filterTables(
            $tables,
            $this->config['entities']['includeTables'],
        );

        $expectedTables = new TablesCollection(... $expectedTables);

        $this->assertEquals($expectedTables, $actualFilteredTables);
    }

    public function testFilterTables_non_existing(): void
    {
        $nrTotalTables = 2;
        $tables = TablesCollectionFactory::make($nrTotalTables);

        $nonExistingTable = self::randomString() . microtime(true);
        $this->config['entities']['includeTables'] = [
            $tables->getAt(0)->getName(),
            $nonExistingTable,
        ];

        $this->createManager();

        $thrownException = null;
        try {
            $this->manager->filterTables(
                $tables,
                $this->config['entities']['includeTables'],
            );
        } catch (Throwable $e) {
            $thrownException = $e;
        }

        $this->assertInstanceOf(TableDoesNotExistException::class, $thrownException);
        $this->assertSame($nonExistingTable, $thrownException->getTableName());
    }

    private function createManager(): void
    {
        $this->manager = new AbstractPhpManager(
            $this->databaseAdapter,
            $this->typeMapper,
            $this->fileSystem,
            $this->typeHint,
        );
    }
}
