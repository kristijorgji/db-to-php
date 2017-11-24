<?php

namespace kristijorgji\DbToPhp\Db\Adapters;

use kristijorgji\DbToPhp\Db\FieldsCollection;
use kristijorgji\DbToPhp\Db\TablesCollection;

interface DatabaseAdapterInterface
{
    /**
     * @return TablesCollection
     */
    public function getTables() : TablesCollection;

    /**
     * @param string $tableName
     * @return FieldsCollection
     */
    public function getFields(string $tableName) : FieldsCollection;
}
