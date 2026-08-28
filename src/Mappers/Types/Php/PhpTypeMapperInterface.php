<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Mappers\Types\Php;

use kristijorgji\DbToPhp\Db\Fields\Field;
use kristijorgji\DbToPhp\Mappers\Types\Exceptions\UnknownDatabaseFieldTypeException;
use kristijorgji\DbToPhp\Rules\Php\PhpType;

interface PhpTypeMapperInterface
{
    /**
     * @throws UnknownDatabaseFieldTypeException
     */
    public function map(Field $field) : PhpType;
}
