<?php

namespace kristijorgji\DbToPhp\Generators\Resolvers;

use kristijorgji\DbToPhp\Rules\Php\PhpType;
use kristijorgji\DbToPhp\Rules\Php\PhpTypes;

class PhpEntityFactoryFieldFunctionResolver
{
    /**
     * @param PhpType $type
     * @param int|null $lengthLimit
     * @param bool|null $signed
     * @return string
     */
    public function resolve(PhpType $type, ?int $lengthLimit, ?bool $signed)
    {
        switch ((string) $type->getType()) {
            case PhpTypes::BOOL:
                return 'self::randomBoolean()';
            case PhpTypes::FLOAT:
                return 'self::randomFloat()';
            case PhpTypes::INTEGER:
                return $this->resolveInteger($lengthLimit, $signed);
            case PhpTypes::STRING:
                return $this->resolveString($lengthLimit);
            case PhpTypes::ARRAY:
                return 'self::randomArray()';
            default:
                throw new \InvalidArgumentException(
                    sprintf('Type %s do not have generator functions yet!', $type->getType()->getSelfKey())
                );
        }
    }

    /**
     * @param int|null $lengthLimit
     * @param bool|null $signed
     * @return string
     */
    private function resolveInteger(?int $lengthLimit, ?bool $signed) : string
    {
        if ($lengthLimit === null) {
            return 'self::randomInt32()';
        }

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
    }

    /**
     * @param int|null $lengthLimit
     * @return string
     */
    private function resolveString(?int $lengthLimit) : string
    {
        if ($lengthLimit === null) {
            return 'self::randomString()';
        }

        return sprintf('self::randomString(%s)', $lengthLimit);
    }
}
