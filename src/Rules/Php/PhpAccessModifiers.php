<?php

namespace kristijorgji\DbToPhp\Rules\Php;

use kristijorgji\DbToPhp\Support\Enum;

class PhpAccessModifiers extends Enum
{
    const PRIVATE = 'private';
    const PROTECTED = 'protected';
    const PUBLIC = 'public';
    const ABSTRACT = 'abstract';
    const FINAL = 'final';
}
