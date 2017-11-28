<?php

namespace kristijorgji\DbToPhp\Managers\Php\Resolvers;

use kristijorgji\DbToPhp\Db\Fields\BinaryField;
use kristijorgji\DbToPhp\Db\Fields\EnumField;
use kristijorgji\DbToPhp\Db\Fields\Field;
use kristijorgji\DbToPhp\Db\Fields\IntegerField;
use kristijorgji\DbToPhp\Db\Fields\TextField;
use kristijorgji\DbToPhp\Rules\Php\PhpType;
use kristijorgji\DbToPhp\Rules\Php\PhpTypes;

class PhpEntityFactoryFieldFunctionResolver
{
    /**
     * @param Field $field
     * @param PhpType $type
     * @return string
     */
    public function resolve(Field $field, PhpType $type) : string
    {
        switch ((string) $type->getType()) {
            case PhpTypes::BOOL:
                return 'self::randomBoolean()';
            case PhpTypes::FLOAT:
                return 'self::randomFloat()';
            case PhpTypes::INTEGER:
                return $this->resolveInteger($field);
            case PhpTypes::STRING:
                return $this->resolveString($field);
            default:
                throw new \InvalidArgumentException(
                    sprintf('Type %s do not have generator functions yet!', $type->getType()->getSelfKey())
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

    /**
     * @param Field $field
     * @return string
     */
    private function resolveString(Field $field) : string
    {
        $lengthLimit = null;

        switch (true) {
            case $field instanceof EnumField:
                return $this->resolveEnum($field);
            case $field instanceof BinaryField:
            case $field instanceof TextField:
                $lengthLimit = $field->getLengthInBytes();
        }

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
