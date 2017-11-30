<?php

namespace kristijorgji\DbToPhp\Managers\Php\Resolvers;

use kristijorgji\DbToPhp\Db\Fields\BinaryField;
use kristijorgji\DbToPhp\Db\Fields\BoolField;
use kristijorgji\DbToPhp\Db\Fields\DecimalField;
use kristijorgji\DbToPhp\Db\Fields\DoubleField;
use kristijorgji\DbToPhp\Db\Fields\EnumField;
use kristijorgji\DbToPhp\Db\Fields\Field;
use kristijorgji\DbToPhp\Db\Fields\FloatField;
use kristijorgji\DbToPhp\Db\Fields\IntegerField;
use kristijorgji\DbToPhp\Db\Fields\TextField;
use kristijorgji\DbToPhp\Db\Fields\YearField;

class PhpEntityFactoryFieldFunctionResolver
{
    /**
     * @param Field $field
     * @return string
     */
    public function resolve(Field $field) : string
    {
        switch (true)
        {
            case $field instanceof BoolField:
                return 'self::randomBoolean()';
            case $field instanceof DoubleField:
            case $field instanceof FloatField:
                return 'self::randomFloat()';
            case $field instanceof EnumField:
                return $this->resolveEnum($field);
            case $field instanceof TextField:
            case $field instanceof BinaryField:
                return $this->resolveString($field);
            case $field instanceof IntegerField:
                return $this->resolveInteger($field);
            case $field instanceof YearField:
                return sprintf('self::randomYear(%s)', $field->getDigits());
            case $field instanceof DecimalField:
                return $this->resolveDecimal($field);
            default:
                throw new \InvalidArgumentException(
                    sprintf('Field %s do not have generator functions yet!', get_class($field))
                );

        }
    }

    /**
     * @param IntegerField $field
     * @return string
     */
    private function resolveInteger(IntegerField $field) : string
    {
        $lengthLimit = $field->getLengthInBits();
        $signed = $field->isSigned();

        if ($lengthLimit === 8) {
            return sprintf('self::random%sInt8()', $signed ? '' : 'Unsigned');
        }

        if ($lengthLimit === 16) {
            return sprintf('self::random%sInt16()', $signed ? '' : 'Unsigned');
        }

        if ($lengthLimit === 24) {
            return sprintf('self::random%sInt24()', $signed ? '' : 'Unsigned');
        }

        if ($lengthLimit === 32) {
            return sprintf('self::random%sInt32()', $signed ? '' : 'Unsigned');
        }

        if ($lengthLimit === 64) {
            return sprintf('self::random%sInt64()', $signed ? '' : 'Unsigned');
        }

        return 'self::randomInt32()';
    }

    private function resolveDecimal(DecimalField $field)
    {
        if ($field->getFractionalPrecision() === 0) {
            if ($field->isSigned()) {
                return sprintf('self::randomNumber(%s)', $field->getDecimalPrecision());
            } else {
                return sprintf('self::randomUnsignedNumber(%s)', $field->getDecimalPrecision());
            }
        }

        return sprintf('self::randomFloat(%s)', $field->getFractionalPrecision());
    }

    /**
     * @param Field $field
     * @return string
     */
    private function resolveString(Field $field) : string
    {
        $lengthLimit = $field->getLengthInBytes();
        return sprintf('self::randomString(rand(0, %s))', $lengthLimit);
    }

    /**
     * @param EnumField $enum
     * @return string
     */
    private function resolveEnum(EnumField $enum) : string
    {
        $args = '';
        $argsCount = $enum->getAllowedValues()->count();
        foreach ($enum->getAllowedValues()->all() as $i => $allowedValue) {
            if ($i < $argsCount - 1) {
                $args .= sprintf('\'%s\', ', addslashes($allowedValue));
            } else {
                $args .= sprintf('\'%s\'', addslashes($allowedValue));
            }
        }

        return sprintf(
            'self::chooseRandomString(%s)',
            $args
        );
    }
}
