<?php

namespace kristijorgji\DbToPhp\Rules\Php;

enum PhpTypes: string
{
    case INTEGER = 'int';
    case STRING = 'string';
    case BOOL = 'bool';
    case FLOAT = 'float';
    case ARRAY = 'array';
    case OBJECT = 'object';
}
