<?php

namespace kristijorgji\DbToPhp\Mappers\Types\Php;

use kristijorgji\DbToPhp\Db\Field;
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

        if (preg_match('#(?=^enum)#i', $fieldType)) {
            $resolvedPhpType = new PhpTypes(PhpTypes::STRING);
        }

        if (!$resolvedPhpType && preg_match('#(?=^char)|(?=^varchar)#i', $fieldType)) {
            $resolvedPhpType = new PhpTypes(PhpTypes::STRING);
        }

        if (!$resolvedPhpType && preg_match('#^(tiny|small|medium|long)*text#i', $fieldType)) {
            $resolvedPhpType = new PhpTypes(PhpTypes::STRING);
        }

        if (!$resolvedPhpType && preg_match('#^(tiny|small|medium|long)*blob#i', $fieldType)) {
            $resolvedPhpType = new PhpTypes(PhpTypes::STRING);
        }

        if (!$resolvedPhpType && preg_match('#(?=^binary)|(?=^varbinary)#i', $fieldType)) {
            $resolvedPhpType = new PhpTypes(PhpTypes::STRING);
        }

        if (!$resolvedPhpType && ($fieldType == 'tinyint(1)' || preg_match('#(?=^bit)#i', $fieldType))) {
            $resolvedPhpType = new PhpTypes(PhpTypes::BOOL);
        } else if (preg_match('#^(tiny|small|medium|big)*int#i', $fieldType)) {
            $resolvedPhpType = new PhpTypes(PhpTypes::INTEGER);
        }

        if (!$resolvedPhpType
            && preg_match('#(?=^float)|(?=^decimal)|(?=^dec)|(?=^double)|(?=^real)|(?=^fixed)#i', $fieldType)) {
            $resolvedPhpType = new PhpTypes(PhpTypes::FLOAT);
        }

        if (!$resolvedPhpType && preg_match('#(?=^time)#i', $fieldType)) {
            $resolvedPhpType = new PhpTypes(PhpTypes::STRING);
        }

        if ($resolvedPhpType === null) {
            throw new UnknownDatabaseFieldTypeException(
                sprintf('The mysql type %s cannot be resolved to any internal type', $fieldType)
            );
        }

        return new PhpType($resolvedPhpType, $nullable);
    }
}
