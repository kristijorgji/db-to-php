<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Db\Adapters;

use kristijorgji\DbToPhp\Db\Fields\FieldsCollection;
use kristijorgji\DbToPhp\Db\TablesCollection;

interface DatabaseAdapterInterface
{
    public function getTables() : TablesCollection;

    public function getFields(string $tableName) : FieldsCollection;
}
