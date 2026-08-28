<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Rules\Php;

class PhpType
{
    public function __construct(private PhpTypes $type, private bool $nullable)
    {
    }

    public function getType(): PhpTypes
    {
        return $this->type;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }
}
