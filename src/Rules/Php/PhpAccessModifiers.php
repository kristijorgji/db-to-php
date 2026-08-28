<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Rules\Php;

enum PhpAccessModifiers: string
{
    case PRIVATE = 'private';
    case PROTECTED = 'protected';
    case PUBLIC = 'public';
    case ABSTRACT = 'abstract';
    case FINAL = 'final';
}
