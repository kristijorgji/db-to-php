<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Mappers\Types\Php;

use kristijorgji\DbToPhp\Db\Fields\BinaryField;
use kristijorgji\DbToPhp\Db\Fields\BoolField;
use kristijorgji\DbToPhp\Db\Fields\DateField;
use kristijorgji\DbToPhp\Db\Fields\DecimalField;
use kristijorgji\DbToPhp\Db\Fields\DoubleField;
use kristijorgji\DbToPhp\Db\Fields\EnumField;
use kristijorgji\DbToPhp\Db\Fields\Field;
use kristijorgji\DbToPhp\Db\Fields\FloatField;
use kristijorgji\DbToPhp\Db\Fields\IntegerField;
use kristijorgji\DbToPhp\Db\Fields\JsonField;
use kristijorgji\DbToPhp\Db\Fields\TextField;
use kristijorgji\DbToPhp\Db\Fields\YearField;
use kristijorgji\DbToPhp\Mappers\Types\Exceptions\UnknownDatabaseFieldTypeException;
use kristijorgji\DbToPhp\Rules\Php\PhpType;
use kristijorgji\DbToPhp\Rules\Php\PhpTypes;
use function get_class;
use function sprintf;

class PhpTypeMapper implements PhpTypeMapperInterface
{
    /**
     * @throws UnknownDatabaseFieldTypeException
     */
    public function map(Field $field) : PhpType
    {
        $resolvedPhpType = null;

        $nullable = $field->isNullable();

        switch(true) {
            case $field instanceof BoolField:
                $resolvedPhpType = PhpTypes::BOOL;
                break;
            case $field instanceof DoubleField:
            case $field instanceof FloatField:
                $resolvedPhpType = PhpTypes::FLOAT;
                break;
            case $field instanceof EnumField:
            case $field instanceof TextField:
            case $field instanceof BinaryField:
            case $field instanceof JsonField:
            case $field instanceof DateField:
                $resolvedPhpType = PhpTypes::STRING;
                break;
            case $field instanceof IntegerField:
            case $field instanceof YearField:
                $resolvedPhpType = PhpTypes::INTEGER;
                break;
            case $field instanceof DecimalField:
                if ($field->getFractionalPrecision() === 0) {
                    $resolvedPhpType = PhpTypes::INTEGER;
                } else {
                    $resolvedPhpType = PhpTypes::FLOAT;
                }
                break;
            default:
                throw new UnknownDatabaseFieldTypeException(
                    sprintf('The field %s cannot be resolved to any internal type', get_class($field)),
                );
        }

        return new PhpType($resolvedPhpType, $nullable);
    }
}
