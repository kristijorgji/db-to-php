<?php

namespace kristijorgji\DbToPhp\Mappers\Types\Php;

use kristijorgji\DbToPhp\Db\Field;
use kristijorgji\DbToPhp\Mappers\Types\Exceptions\UnknownDatabaseFieldTypeException;
use kristijorgji\DbToPhp\Rules\Php\PhpType;

interface PhpTypeMapperInterface
{
    /**
     * @param Field $field
     * @return PhpType
     * @throws UnknownDatabaseFieldTypeException
     */
    public function map(Field $field) : PhpType;
}
