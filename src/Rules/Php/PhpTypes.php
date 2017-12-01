<?php

namespace kristijorgji\DbToPhp\Rules\Php;

use kristijorgji\DbToPhp\Support\Enum;

class PhpTypes extends Enum
{
    const INTEGER = 'int';
    const STRING = 'string';
    const BOOL = 'bool';
    const FLOAT = 'float';
    const ARRAY = 'array';
    const OBJECT = 'object';
}
