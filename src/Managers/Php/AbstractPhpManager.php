<?php

namespace kristijorgji\DbToPhp\Managers\Php;

use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterInterface;
use kristijorgji\DbToPhp\Db\TablesCollection;
use kristijorgji\DbToPhp\FileSystem\FileSystemInterface;
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
     * @param DatabaseAdapterInterface $databaseAdapter
     * @param PhpTypeMapperInterface $typeMapper
     * @param FileSystemInterface $fileSystem
     */
    public function __construct(
        DatabaseAdapterInterface $databaseAdapter,
        PhpTypeMapperInterface $typeMapper,
        FileSystemInterface $fileSystem
    ) {
        $this->databaseAdapter = $databaseAdapter;
        $this->typeMapper = $typeMapper;
        $this->fileSystem = $fileSystem;
    }

    /**
     * @param TablesCollection $tables
     * @param array $filter
     * @return TablesCollection
     */
    public function filterTables(TablesCollection $tables, array $filter) : TablesCollection
    {
        if ($filter[0] === '*') {
            return $tables;
        }

        $selectedTables = [];

        foreach ($tables->all() as $table) {
            if (in_array($table->getName(), $filter)) {
                $selectedTables[] = $table;
            }
        }

        return new TablesCollection(...$selectedTables);
    }
}
