<?php

namespace kristijorgji\DbToPhp\Mappers\Types\Php;

use kristijorgji\DbToPhp\Db\Fields\BinaryField;
use kristijorgji\DbToPhp\Db\Fields\BoolField;
use kristijorgji\DbToPhp\Db\Fields\DecimalField;
use kristijorgji\DbToPhp\Db\Fields\DoubleField;
use kristijorgji\DbToPhp\Db\Fields\EnumField;
use kristijorgji\DbToPhp\Db\Fields\Field;
use kristijorgji\DbToPhp\Db\Fields\FloatField;
use kristijorgji\DbToPhp\Db\Fields\IntegerField;
use kristijorgji\DbToPhp\Db\Fields\TextField;
use kristijorgji\DbToPhp\Mappers\Types\Exceptions\UnknownDatabaseFieldTypeException;
use kristijorgji\DbToPhp\Rules\Php\PhpType;
use kristijorgji\DbToPhp\Rules\Php\PhpTypes;

class MySqlPhpTypeMapper implements PhpTypeMapperInterface
{
    /**
     * @param Field $field
     * @return PhpType
     * @throws UnknownDatabaseFieldTypeException
     */
    public function map(Field $field) : PhpType
    {
        $resolvedPhpType = null;

        $fieldType = $field->getType();
        $nullable = $field->isNullable();

        switch(true) {
            case $field instanceof BoolField:
                $resolvedPhpType = new PhpTypes(PhpTypes::BOOL);
                break;
            case $field instanceof DoubleField:
            case $field instanceof FloatField:
                $resolvedPhpType = new PhpTypes(PhpTypes::FLOAT);
                break;
            case $field instanceof EnumField:
            case $field instanceof TextField:
            case $field instanceof BinaryField:
                $resolvedPhpType = new PhpTypes(PhpTypes::STRING);
                break;
            case $field instanceof IntegerField:
                $resolvedPhpType = new PhpTypes(PhpTypes::INTEGER);
                break;
            case $field instanceof DecimalField:
                if ($field->getFractionalPrecision() === 0) {
                    $resolvedPhpType = new PhpTypes(PhpTypes::INTEGER);
                } else {
                    $resolvedPhpType = new PhpTypes(PhpTypes::FLOAT);
                }
                break;
            default:
                throw new UnknownDatabaseFieldTypeException(
                    sprintf('The mysql type %s cannot be resolved to any internal type', $fieldType)
                );
        }

        return new PhpType($resolvedPhpType, $nullable);
    }
}
