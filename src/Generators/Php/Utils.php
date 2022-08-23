<?php

namespace kristijorgji\DbToPhp\Generators\Php;

use kristijorgji\DbToPhp\Rules\Php\PhpObjectType;
use kristijorgji\DbToPhp\Rules\Php\PhpType;

final class Utils
{
    public static function resolveTypeForAnnotation(PhpType $type) : string
    {
        $nullableText = $type->isNullable() === true ? '|null' :  '';
        if ($type instanceof PhpObjectType) {
            return $type->getClassName() . $nullableText;
        }

        return $type->getType() . $nullableText;
    }

    public static function resolveType(PhpType $type) : string
    {
        $nullablePrefix = $type->isNullable() === true ? '?' :  '';
        if ($type instanceof PhpObjectType) {
            return $nullablePrefix . $type->getClassName();
        }

        return $nullablePrefix . $type->getType();
    }
}
