<?php

namespace kristijorgji\DbToPhp\Managers\Php;

use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterInterface;
use kristijorgji\DbToPhp\Db\TablesCollection;
use kristijorgji\DbToPhp\FileSystem\FileSystemInterface;
use kristijorgji\DbToPhp\Managers\Exceptions\TableDoesNotExistException;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapperInterface;

class AbstractPhpManager
{
    /**
     * @var DatabaseAdapterInterface
     */
    protected $databaseAdapter;

    /**
     * @var PhpTypeMapperInterface
     */
    protected $typeMapper;

    /**
     * @var FileSystemInterface
     */
    protected $fileSystem;

    /**
     * @var bool
     */
    protected $typeHint;

    /**
     * @param DatabaseAdapterInterface $databaseAdapter
     * @param PhpTypeMapperInterface $typeMapper
     * @param FileSystemInterface $fileSystem
     * @param bool $typeHint
     */
    public function __construct(
        DatabaseAdapterInterface $databaseAdapter,
        PhpTypeMapperInterface $typeMapper,
        FileSystemInterface $fileSystem,
        bool $typeHint
    ) {
        $this->databaseAdapter = $databaseAdapter;
        $this->typeMapper = $typeMapper;
        $this->fileSystem = $fileSystem;
        $this->typeHint = $typeHint;
    }

    /**
     * @param string $qualifiedClassName
     * @return string
     */
    public function stripClassName(string $qualifiedClassName) : string
    {
        $parts = explode('\\', $qualifiedClassName);
        return array_pop($parts);
    }

    /**
     * @param TablesCollection $tables
     * @param string[] $selectedTableNames
     * @return TablesCollection
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

    /**
     * @param TablesCollection $tablesCollection
     * @return array
     */
    protected function formTablesMap(TablesCollection $tablesCollection) : array
    {
        $tablesMap = [];

        foreach ($tablesCollection->all() as $table) {
            $tablesMap[$table->getName()] = $table;
        }

        return $tablesMap;
    }
}
