<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Managers\Php;

use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterInterface;
use kristijorgji\DbToPhp\Db\TablesCollection;
use kristijorgji\DbToPhp\FileSystem\FileSystemInterface;
use kristijorgji\DbToPhp\Managers\Exceptions\TableDoesNotExistException;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapperInterface;
use function array_key_exists;
use function array_pop;
use function explode;

class AbstractPhpManager
{
    public function __construct(
        protected DatabaseAdapterInterface $databaseAdapter,
        protected PhpTypeMapperInterface $typeMapper,
        protected FileSystemInterface $fileSystem,
        protected bool $typeHint,
    ) {
    }

    public function stripClassName(string $qualifiedClassName) : string
    {
        $parts = explode('\\', $qualifiedClassName);
        return array_pop($parts);
    }

    /**
     * @param array<string> $selectedTableNames
     * @throws TableDoesNotExistException
     */
    public function filterTables(TablesCollection $tables, array $selectedTableNames) : TablesCollection
    {
        if ($selectedTableNames[0] === '*') {
            return $tables;
        }

        $tablesMap = $this->formTablesMap($tables);
        $selectedTables = [];

        foreach ($selectedTableNames as $selectedTableName) {
            if (! array_key_exists($selectedTableName, $tablesMap)) {
                throw new TableDoesNotExistException($selectedTableName);
            }

            $selectedTables[] = $tablesMap[$selectedTableName];
        }

        return new TablesCollection(...$selectedTables);
    }

    protected function formTablesMap(TablesCollection $tablesCollection) : array
    {
        $tablesMap = [];

        foreach ($tablesCollection->all() as $table) {
            $tablesMap[$table->getName()] = $table;
        }

        return $tablesMap;
    }
}
